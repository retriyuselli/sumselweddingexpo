<?php

namespace App\Services;

use App\Models\Expo;
use App\Models\Pengeluaran;
use App\Models\RekeningTujuan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PengeluaranExcelImporter
{
    /**
     * Canonical headers used in the downloadable template.
     *
     * @var list<string>
     */
    public const HEADERS = [
        'nama_pengeluaran',
        'nominal',
        'tanggal',
        'keterangan',
        'rek_transfer',
        'nama_rekening_penerima',
    ];

    /**
     * @var array<string, string>
     */
    private const HEADER_ALIASES = [
        'nama_pengeluaran' => 'nama_pengeluaran',
        'nama pengeluaran' => 'nama_pengeluaran',
        'nama' => 'nama_pengeluaran',
        'nominal' => 'nominal',
        'jumlah' => 'nominal',
        'amount' => 'nominal',
        'tanggal' => 'tanggal',
        'tgl' => 'tanggal',
        'date' => 'tanggal',
        'keterangan' => 'keterangan',
        'catatan' => 'keterangan',
        'nama_expo' => 'nama_expo',
        'nama expo' => 'nama_expo',
        'expo' => 'nama_expo',
        'periode' => 'periode',
        'periode_expo' => 'periode',
        'periode expo' => 'periode',
        'season' => 'periode',
        'tanggal_mulai_expo' => 'tanggal_mulai_expo',
        'tanggal mulai expo' => 'tanggal_mulai_expo',
        'tgl_mulai_expo' => 'tanggal_mulai_expo',
        'tgl mulai expo' => 'tanggal_mulai_expo',
        'rekening_tujuan' => 'rekening_tujuan',
        'rekening tujuan' => 'rekening_tujuan',
        'sumber_dana' => 'rekening_tujuan',
        'sumber dana' => 'rekening_tujuan',
        'bank' => 'rekening_tujuan',
        'rek_transfer' => 'rek_transfer',
        'no_rekening_penerima' => 'rek_transfer',
        'no rekening penerima' => 'rek_transfer',
        'nama_rekening_penerima' => 'nama_rekening_penerima',
        'nama rekening penerima' => 'nama_rekening_penerima',
    ];

    /**
     * Preview row count / validation without writing to DB.
     *
     * @return array{rows: int, headers_ok: bool, message: ?string}
     */
    public function preview(string $filePath): array
    {
        $reader = new XlsxReader;
        $reader->open($filePath);

        $rows = 0;
        $headersOk = false;
        $message = null;

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $headers = null;

                foreach ($sheet->getRowIterator() as $row) {
                    $values = $this->normalizeRowValues($row->toArray());

                    if ($this->isEmptyRow($values)) {
                        continue;
                    }

                    if ($headers === null) {
                        $headers = $this->mapHeaders($values);
                        $headersOk = isset($headers['nama_pengeluaran'], $headers['nominal'], $headers['tanggal']);

                        if (! $headersOk) {
                            $message = 'Header wajib tidak ditemukan. Pastikan ada kolom: nama_pengeluaran, nominal, tanggal.';
                        }

                        continue;
                    }

                    $rows++;
                }

                break;
            }
        } finally {
            $reader->close();
        }

        return [
            'rows' => $rows,
            'headers_ok' => $headersOk,
            'message' => $message,
        ];
    }

    /**
     * @return array{imported: int, failed: int, errors: list<string>}
     */
    public function import(
        string $filePath,
        ?int $userId = null,
        ?int $expoId = null,
        ?int $rekeningTujuanId = null,
    ): array {
        $userId ??= Auth::id();
        $imported = 0;
        $failed = 0;
        $errors = [];

        if ($expoId !== null && ! Expo::query()->whereKey($expoId)->exists()) {
            throw new \RuntimeException('Expo yang dipilih tidak ditemukan.');
        }

        if ($rekeningTujuanId !== null && ! RekeningTujuan::query()->whereKey($rekeningTujuanId)->exists()) {
            throw new \RuntimeException('Rekening tujuan yang dipilih tidak ditemukan.');
        }

        $reader = new XlsxReader;
        $reader->open($filePath);

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $headers = null;
                $rowNumber = 0;

                foreach ($sheet->getRowIterator() as $row) {
                    $rowNumber++;
                    $values = $this->normalizeRowValues($row->toArray());

                    if ($this->isEmptyRow($values)) {
                        continue;
                    }

                    if ($headers === null) {
                        $headers = $this->mapHeaders($values);

                        if (! isset($headers['nama_pengeluaran'], $headers['nominal'], $headers['tanggal'])) {
                            throw new \RuntimeException(
                                'Header wajib tidak ditemukan. Pastikan ada kolom: nama_pengeluaran, nominal, tanggal.'
                            );
                        }

                        continue;
                    }

                    try {
                        $payload = $this->mapRow($headers, $values, $expoId, $rekeningTujuanId);
                        $this->createPengeluaran($payload, $userId);
                        $imported++;
                    } catch (Throwable $e) {
                        $failed++;
                        $errors[] = "Baris {$rowNumber}: ".$e->getMessage();
                    }
                }

                // Only process the first sheet
                break;
            }
        } finally {
            $reader->close();
        }

        return compact('imported', 'failed', 'errors');
    }

    public function downloadTemplate(): StreamedResponse
    {
        $fileName = 'template-import-pengeluaran.xlsx';

        return response()->streamDownload(function () use ($fileName): void {
            $writer = new XlsxWriter;
            $tempPath = storage_path('app/tmp/'.$fileName);
            if (! is_dir(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            $writer->openToFile($tempPath);
            $writer->addRow(Row::fromValues(self::HEADERS));
            $writer->addRow(Row::fromValues([
                'Sewa Venue Mall',
                45000000,
                '2026-08-01',
                'Biaya sewa area expo 3 hari',
                '1234567890',
                'Manajemen Mall',
            ]));
            $writer->addRow(Row::fromValues([
                'Dekorasi Panggung',
                12500000,
                '01/08/2026',
                'Backdrop area utama',
                '9876543210',
                'CV Dekorasi Jaya',
            ]));
            $writer->close();

            echo file_get_contents($tempPath);
            @unlink($tempPath);
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @param  array<int, mixed>  $values
     * @return array<string, int>
     */
    private function mapHeaders(array $values): array
    {
        $map = [];

        foreach ($values as $index => $value) {
            $key = $this->normalizeHeader((string) $value);
            if ($key === '' || ! isset(self::HEADER_ALIASES[$key])) {
                continue;
            }

            $canonical = self::HEADER_ALIASES[$key];
            $map[$canonical] ??= $index;
        }

        return $map;
    }

    /**
     * @param  array<string, int>  $headers
     * @param  array<int, mixed>  $values
     * @return array<string, mixed>
     */
    private function mapRow(
        array $headers,
        array $values,
        ?int $forcedExpoId = null,
        ?int $forcedRekeningTujuanId = null,
    ): array {
        $get = function (string $key) use ($headers, $values): mixed {
            if (! isset($headers[$key])) {
                return null;
            }

            return $values[$headers[$key]] ?? null;
        };

        $nama = trim((string) ($get('nama_pengeluaran') ?? ''));
        if ($nama === '') {
            throw new \RuntimeException('nama_pengeluaran wajib diisi.');
        }

        $nominal = $this->parseNominal($get('nominal'));
        if ($nominal < 1) {
            throw new \RuntimeException('nominal harus lebih dari 0.');
        }

        $tanggal = $this->parseDate($get('tanggal'));
        if (! $tanggal) {
            throw new \RuntimeException('tanggal tidak valid. Gunakan format YYYY-MM-DD atau DD/MM/YYYY.');
        }

        if ($forcedRekeningTujuanId !== null) {
            $rekeningId = $forcedRekeningTujuanId;
        } else {
            $rekeningValue = trim((string) ($get('rekening_tujuan') ?? ''));
            $rekeningId = $this->resolveRekeningId($rekeningValue);
            if (! $rekeningId) {
                throw new \RuntimeException(
                    $rekeningValue === ''
                        ? 'rekening_tujuan wajib dipilih (di konfirmasi import atau diisi di Excel).'
                        : "rekening_tujuan \"{$rekeningValue}\" tidak ditemukan."
                );
            }
        }

        $expoId = $forcedExpoId ?? $this->resolveExpoId(
            $this->nullableString($get('nama_expo')),
            $this->nullableString($get('periode')),
            $this->parseDate($get('tanggal_mulai_expo')),
        );

        return [
            'nama_pengeluaran' => $nama,
            'nominal' => $nominal,
            'tanggal' => $tanggal,
            'keterangan' => $this->nullableString($get('keterangan')),
            'expo_id' => $expoId,
            'rekening_tujuan_id' => $rekeningId,
            'rek_transfer' => $this->nullableString($get('rek_transfer')),
            'nama_rekening_penerima' => $this->nullableString($get('nama_rekening_penerima')),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function createPengeluaran(array $payload, ?int $userId): void
    {
        Pengeluaran::create([
            ...$payload,
            'bukti_transfer' => null,
            'user_id' => $userId,
        ]);
    }

    /**
     * Resolve expo by nama + periode + tanggal mulai.
     * Nama saja tidak cukup karena bisa dipakai berulang setiap season.
     */
    private function resolveExpoId(?string $namaExpo, ?string $periode, ?Carbon $tanggalMulai): ?int
    {
        if ($namaExpo === null && $periode === null && $tanggalMulai === null) {
            return null;
        }

        $query = Expo::query();

        if ($namaExpo !== null) {
            $query->where(function ($q) use ($namaExpo): void {
                $q->where('nama_expo', $namaExpo)
                    ->orWhere('nama_expo', 'like', '%'.$namaExpo.'%');
            });
        }

        if ($periode !== null) {
            $query->where(function ($q) use ($periode): void {
                $q->where('periode', $periode)
                    ->orWhere('periode', 'like', '%'.$periode.'%');
            });
        }

        if ($tanggalMulai !== null) {
            $query->whereDate('tanggal_mulai', $tanggalMulai->toDateString());
        }

        $matches = $query->orderByDesc('tanggal_mulai')->get();

        if ($matches->isEmpty()) {
            $hint = collect([
                $namaExpo ? "nama_expo=\"{$namaExpo}\"" : null,
                $periode ? "periode=\"{$periode}\"" : null,
                $tanggalMulai ? 'tanggal_mulai_expo='.$tanggalMulai->toDateString() : null,
            ])->filter()->implode(', ');

            throw new \RuntimeException("Expo tidak ditemukan ({$hint}).");
        }

        if ($matches->count() === 1) {
            return $matches->first()->id;
        }

        // Ambiguous: same nama without enough disambiguators
        if ($periode === null && $tanggalMulai === null) {
            $options = $matches
                ->map(fn (Expo $expo) => $expo->labelForSelect())
                ->take(5)
                ->implode(' | ');

            throw new \RuntimeException(
                "Ditemukan {$matches->count()} expo dengan nama serupa. Isi kolom periode dan/atau tanggal_mulai_expo. Contoh: {$options}"
            );
        }

        // Prefer exact periode match if available
        if ($periode !== null) {
            $exactPeriode = $matches->firstWhere('periode', $periode);
            if ($exactPeriode) {
                return $exactPeriode->id;
            }
        }

        // Prefer exact nama + tanggal_mulai
        if ($namaExpo !== null && $tanggalMulai !== null) {
            $exact = $matches->first(
                fn (Expo $expo) => $expo->nama_expo === $namaExpo
                    && $expo->tanggal_mulai?->toDateString() === $tanggalMulai->toDateString()
            );
            if ($exact) {
                return $exact->id;
            }
        }

        $options = $matches
            ->map(fn (Expo $expo) => $expo->labelForSelect())
            ->take(5)
            ->implode(' | ');

        throw new \RuntimeException(
            "Expo ambigu ({$matches->count()} cocokan). Perjelas periode/tanggal_mulai_expo. Pilihan: {$options}"
        );
    }

    private function resolveRekeningId(string $value): ?int
    {
        if ($value === '') {
            return RekeningTujuan::query()->value('id');
        }

        $rekening = RekeningTujuan::query()
            ->where(function ($query) use ($value): void {
                $query->where('nama_bank', $value)
                    ->orWhere('nomor_rekening', $value)
                    ->orWhere('nama_pemilik', $value)
                    ->orWhereRaw("CONCAT(nama_bank, ' - ', nomor_rekening) = ?", [$value]);
            })
            ->first();

        return $rekening?->id;
    }

    private function parseNominal(mixed $value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_int($value) || is_float($value)) {
            return (int) round((float) $value);
        }

        $raw = trim((string) $value);
        $raw = str_ireplace(['rp', 'idr'], '', $raw);
        $raw = trim($raw);

        if (preg_match('/^\d{1,3}(\.\d{3})+(,\d+)?$/', $raw)) {
            $raw = str_replace('.', '', $raw);
            $raw = str_replace(',', '.', $raw);
        } else {
            $raw = str_replace([',', ' '], '', $raw);
        }

        $raw = preg_replace('/[^0-9.\-]/', '', $raw) ?? '0';

        return (int) round((float) $raw);
    }

    private function parseDate(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance(\DateTimeImmutable::createFromInterface($value));
        }

        $raw = trim((string) $value);

        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d', 'd M Y', 'd F Y'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $raw);

                if ($date !== false) {
                    return $date->startOfDay();
                }
            } catch (Throwable) {
                // try next
            }
        }

        try {
            return Carbon::parse($raw)->startOfDay();
        } catch (Throwable) {
            return null;
        }
    }

    private function normalizeHeader(string $value): string
    {
        $value = strtolower(trim($value));
        $value = str_replace(['_', '-'], ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return $value;
    }

    /**
     * @param  array<int, mixed>  $values
     * @return array<int, mixed>
     */
    private function normalizeRowValues(array $values): array
    {
        return array_map(function (mixed $value): mixed {
            if ($value instanceof \DateTimeInterface) {
                return $value;
            }

            if (is_string($value)) {
                return trim($value);
            }

            return $value;
        }, $values);
    }

    /**
     * @param  array<int, mixed>  $values
     */
    private function isEmptyRow(array $values): bool
    {
        foreach ($values as $value) {
            if ($value instanceof \DateTimeInterface) {
                return false;
            }

            if (is_string($value) && trim($value) !== '') {
                return false;
            }

            if (is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    private function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }
}
