<?php

namespace App\Filament\Resources\Pengeluarans\Pages;

use App\Filament\Resources\Pengeluarans\PengeluaranResource;
use App\Filament\Resources\Pengeluarans\Widgets\PengeluaranOverview;
use App\Models\Expo;
use App\Models\RekeningTujuan;
use App\Services\ExpoResolver;
use App\Services\PengeluaranExcelImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

class ListPengeluarans extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = PengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplatePengeluaran')
                ->label('Template Excel')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->action(fn (PengeluaranExcelImporter $importer) => $importer->downloadTemplate()),

            Action::make('importPengeluaranExcel')
                ->label('Import Excel')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->color('success')
                ->modalHeading('Import Pengeluaran dari Excel')
                ->modalSubmitActionLabel('Import Sekarang')
                ->steps([
                    Step::make('Upload')
                        ->description('Unggah file Excel (.xlsx)')
                        ->schema([
                            FileUpload::make('file')
                                ->label('File Excel (.xlsx)')
                                ->acceptedFileTypes([
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.ms-excel',
                                ])
                                ->rules(['required', 'file', 'mimes:xlsx'])
                                ->disk('local')
                                ->directory('imports/pengeluaran')
                                ->visibility('private')
                                ->required()
                                ->helperText('Kolom: nama_pengeluaran, nominal, tanggal, keterangan, rek_transfer, nama_rekening_penerima. Expo & rekening sumber dipilih di langkah berikutnya.'),
                        ]),

                    Step::make('Konfirmasi')
                        ->description('Pilih expo dan rekening sumber sebelum data disimpan')
                        ->schema([
                            Placeholder::make('file_preview')
                                ->label('Ringkasan file')
                                ->content(function (Get $get, PengeluaranExcelImporter $importer): HtmlString {
                                    $path = $this->resolveUploadedPath($get('file'));

                                    if (! $path || ! is_file($path)) {
                                        return new HtmlString('<span class="text-sm text-gray-500">Upload file di langkah sebelumnya terlebih dahulu.</span>');
                                    }

                                    try {
                                        $preview = $importer->preview($path);
                                    } catch (Throwable $e) {
                                        return new HtmlString('<span class="text-sm text-danger-600">Gagal membaca file: '.e($e->getMessage()).'</span>');
                                    }

                                    if (! $preview['headers_ok']) {
                                        return new HtmlString('<span class="text-sm text-danger-600">'.e($preview['message'] ?? 'Format header tidak valid.').'</span>');
                                    }

                                    $count = number_format($preview['rows']);

                                    return new HtmlString(
                                        '<div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm dark:border-gray-700 dark:bg-gray-800">'
                                        .'<div class="font-medium text-gray-950 dark:text-white">Ditemukan <strong>'.$count.'</strong> baris data siap diimport.</div>'
                                        .'<div class="mt-1 text-gray-600 dark:text-gray-300">Semua baris akan dikaitkan ke expo dan rekening sumber yang Anda pilih di bawah.</div>'
                                        .'</div>'
                                    );
                                }),

                            Select::make('expo_id')
                                ->label('Masukkan data ke Expo mana?')
                                ->options(
                                    fn (): array => Expo::query()
                                        ->orderByDesc('tanggal_mulai')
                                        ->get()
                                        ->mapWithKeys(fn (Expo $expo) => [$expo->id => $expo->labelForSelect()])
                                        ->all()
                                )
                                ->default(fn (ExpoResolver $expoResolver): ?int => $expoResolver->nearestActive()?->id)
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->required()
                                ->helperText('Label menampilkan nama · periode · tanggal pelaksanaan.'),

                            Select::make('rekening_tujuan_id')
                                ->label('Sumber dana / No Rekening mana?')
                                ->options(
                                    fn (): array => RekeningTujuan::query()
                                        ->orderBy('nama_bank')
                                        ->get()
                                        ->mapWithKeys(fn (RekeningTujuan $rekening) => [
                                            $rekening->id => $rekening->nama_bank.' - '.$rekening->nomor_rekening.' ('.$rekening->nama_pemilik.')',
                                        ])
                                        ->all()
                                )
                                ->default(fn (): ?int => RekeningTujuan::query()->value('id'))
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->required()
                                ->helperText('Rekening perusahaan yang dipakai sebagai sumber dana untuk semua baris import.'),
                        ]),
                ])
                ->action(function (array $data, PengeluaranExcelImporter $importer): void {
                    $file = $data['file'] ?? null;
                    $expoId = isset($data['expo_id']) ? (int) $data['expo_id'] : null;
                    $rekeningId = isset($data['rekening_tujuan_id']) ? (int) $data['rekening_tujuan_id'] : null;

                    if (! $file) {
                        Notification::make()
                            ->title('File tidak ditemukan')
                            ->danger()
                            ->send();

                        return;
                    }

                    if (! $expoId) {
                        Notification::make()
                            ->title('Expo belum dipilih')
                            ->body('Pilih expo tujuan sebelum mengimport.')
                            ->danger()
                            ->send();

                        return;
                    }

                    if (! $rekeningId) {
                        Notification::make()
                            ->title('Rekening belum dipilih')
                            ->body('Pilih nomor rekening sumber dana sebelum mengimport.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $path = $this->resolveUploadedPath($file);

                    if (! $path || ! is_file($path)) {
                        Notification::make()
                            ->title('Gagal membaca file upload')
                            ->danger()
                            ->send();

                        return;
                    }

                    $expo = Expo::query()->find($expoId);
                    $rekening = RekeningTujuan::query()->find($rekeningId);

                    try {
                        $result = $importer->import($path, auth()->id(), $expoId, $rekeningId);
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Import gagal')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        return;
                    } finally {
                        $this->cleanupUploadedFile($file);
                    }

                    $expoLabel = $expo?->labelForSelect() ?? ('#'.$expoId);
                    $rekeningLabel = $rekening
                        ? $rekening->nama_bank.' - '.$rekening->nomor_rekening
                        : ('#'.$rekeningId);

                    $body = "Expo: {$expoLabel}\nRekening: {$rekeningLabel}\nBerhasil: {$result['imported']} baris";

                    if ($result['failed'] > 0) {
                        $body .= " · Gagal: {$result['failed']} baris";
                        $preview = implode("\n", array_slice($result['errors'], 0, 5));
                        if ($preview !== '') {
                            $body .= "\n".$preview;
                        }
                        if (count($result['errors']) > 5) {
                            $body .= "\n… dan ".(count($result['errors']) - 5).' error lainnya';
                        }
                    }

                    $notification = Notification::make()
                        ->title('Import selesai')
                        ->body($body);

                    if ($result['failed'] > 0) {
                        $notification->warning()->persistent();
                    } else {
                        $notification->success();
                    }

                    $notification->send();
                }),

            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PengeluaranOverview::class,
        ];
    }

    private function resolveUploadedPath(mixed $file): ?string
    {
        if ($file instanceof TemporaryUploadedFile) {
            return $file->getRealPath() ?: null;
        }

        if (is_string($file) && $file !== '') {
            if (is_file($file)) {
                return $file;
            }

            $storagePath = Storage::disk('local')->path($file);
            if (is_file($storagePath)) {
                return $storagePath;
            }
        }

        if (is_array($file)) {
            $first = reset($file);

            return $first ? $this->resolveUploadedPath($first) : null;
        }

        return null;
    }

    private function cleanupUploadedFile(mixed $file): void
    {
        if (is_string($file) && $file !== '' && Storage::disk('local')->exists($file)) {
            Storage::disk('local')->delete($file);
        }

        if (is_array($file)) {
            foreach ($file as $item) {
                $this->cleanupUploadedFile($item);
            }
        }
    }
}
