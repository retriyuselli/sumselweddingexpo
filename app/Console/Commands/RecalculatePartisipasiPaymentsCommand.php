<?php

namespace App\Console\Commands;

use App\Models\Partisipasi;
use Illuminate\Console\Command;

class RecalculatePartisipasiPaymentsCommand extends Command
{
    protected $signature = 'partisipasi:recalculate-payments
                            {--dry-run : Tampilkan perubahan tanpa menyimpan}';

    protected $description = 'Hitung ulang harga_bersih, sisa_pembayaran, dan status_pembayaran (termasuk potongan barter)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $updated = 0;

        Partisipasi::query()
            ->with(['dataPembayarans', 'vendor:id,nama_vendor'])
            ->orderBy('id')
            ->chunkById(100, function ($rows) use (&$updated, $dryRun) {
                foreach ($rows as $partisipasi) {
                    $before = [
                        'harga_bersih' => (int) $partisipasi->harga_bersih,
                        'total_pembayaran' => (int) $partisipasi->total_pembayaran,
                        'sisa_pembayaran' => (int) $partisipasi->sisa_pembayaran,
                        'status_pembayaran' => (string) $partisipasi->status_pembayaran,
                    ];

                    $partisipasi->recalculatePaymentStatus();

                    $after = [
                        'harga_bersih' => (int) $partisipasi->harga_bersih,
                        'total_pembayaran' => (int) $partisipasi->total_pembayaran,
                        'sisa_pembayaran' => (int) $partisipasi->sisa_pembayaran,
                        'status_pembayaran' => (string) $partisipasi->status_pembayaran,
                    ];

                    if ($before === $after) {
                        continue;
                    }

                    $updated++;
                    $this->line(sprintf(
                        '#%d %s: %s → %s | sisa %s → %s | bersih %s → %s',
                        $partisipasi->id,
                        $partisipasi->vendor?->nama_vendor ?? 'vendor?',
                        $before['status_pembayaran'],
                        $after['status_pembayaran'],
                        number_format($before['sisa_pembayaran'], 0, ',', '.'),
                        number_format($after['sisa_pembayaran'], 0, ',', '.'),
                        number_format($before['harga_bersih'], 0, ',', '.'),
                        number_format($after['harga_bersih'], 0, ',', '.'),
                    ));

                    if (! $dryRun) {
                        $partisipasi->saveQuietly();
                    }
                }
            });

        $this->info($dryRun
            ? "Dry-run selesai. {$updated} partisipasi perlu diupdate."
            : "Selesai. {$updated} partisipasi berhasil diupdate.");

        return self::SUCCESS;
    }
}
