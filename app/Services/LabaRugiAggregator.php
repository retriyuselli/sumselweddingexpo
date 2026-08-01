<?php

namespace App\Services;

use App\Models\DataPembayaran;
use App\Models\Expo;
use App\Models\Partisipasi;
use App\Models\Pengeluaran;
use App\Models\Sponsor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LabaRugiAggregator
{
    /**
     * Compute finance totals for all expos in a handful of grouped queries.
     *
     * Cash basis: pemasukan partisipasi = SUM(data_pembayarans.nominal).
     *
     * @return array{
     *     partisipasi: Collection<int, float>,
     *     sponsor: Collection<int, float>,
     *     pengeluaran: Collection<int, float>,
     *     piutang: Collection<int, float>,
     *     barter: Collection<int, float>
     * }
     */
    public function totalsByExpo(): array
    {
        $partisipasi = DataPembayaran::query()
            ->join('partisipasis', 'partisipasis.id', '=', 'data_pembayarans.partisipasi_id')
            ->whereNull('partisipasis.deleted_at')
            ->select('partisipasis.expo_id', DB::raw('COALESCE(SUM(data_pembayarans.nominal), 0) as total'))
            ->groupBy('partisipasis.expo_id')
            ->pluck('total', 'expo_id')
            ->map(fn ($v) => (float) $v);

        $sponsor = Sponsor::query()
            ->select('expo_id', DB::raw('COALESCE(SUM(nominal), 0) as total'))
            ->groupBy('expo_id')
            ->pluck('total', 'expo_id')
            ->map(fn ($v) => (float) $v);

        $pengeluaran = Pengeluaran::query()
            ->select('expo_id', DB::raw('COALESCE(SUM(nominal), 0) as total'))
            ->groupBy('expo_id')
            ->pluck('total', 'expo_id')
            ->map(fn ($v) => (float) $v);

        // Samakan dengan Laporan Piutang & PDF: sisa > 0 (bukan hanya status != Lunas)
        $piutang = Partisipasi::query()
            ->where('sisa_pembayaran', '>', 0)
            ->select('expo_id', DB::raw('COALESCE(SUM(sisa_pembayaran), 0) as total'))
            ->groupBy('expo_id')
            ->pluck('total', 'expo_id')
            ->map(fn ($v) => (float) $v);

        $barter = Partisipasi::query()
            ->where('is_barter', true)
            ->select('expo_id', DB::raw('COALESCE(SUM(COALESCE(barter_nominal, 0)), 0) as total'))
            ->groupBy('expo_id')
            ->pluck('total', 'expo_id')
            ->map(fn ($v) => (float) $v);

        return compact('partisipasi', 'sponsor', 'pengeluaran', 'piutang', 'barter');
    }

    /**
     * @return array{pemasukan: float, pengeluaran: float, piutang: float, laba_rugi: float}
     */
    public function globalTotals(): array
    {
        $byExpo = $this->totalsByExpo();

        $pemasukan = (float) $byExpo['partisipasi']->sum() + (float) $byExpo['sponsor']->sum();
        $pengeluaran = (float) $byExpo['pengeluaran']->sum();
        $piutang = (float) $byExpo['piutang']->sum();

        return [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'piutang' => $piutang,
            'laba_rugi' => $pemasukan - $pengeluaran,
        ];
    }

    public function forExpo(Expo $expo): array
    {
        $all = $this->totalsByExpo();
        $id = $expo->id;

        $partisipasi = (float) ($all['partisipasi'][$id] ?? 0);
        $sponsor = (float) ($all['sponsor'][$id] ?? 0);
        $pengeluaran = (float) ($all['pengeluaran'][$id] ?? 0);
        $piutang = (float) ($all['piutang'][$id] ?? 0);
        $barter = (float) ($all['barter'][$id] ?? 0);
        $pemasukan = $partisipasi + $sponsor;

        return [
            'partisipasi' => $partisipasi,
            'sponsor' => $sponsor,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'piutang' => $piutang,
            'barter' => $barter,
            'laba_rugi' => $pemasukan - $pengeluaran,
        ];
    }

    public static function formatRupiah(float|int $amount): string
    {
        return number_format((float) $amount, 0, ',', '.');
    }
}
