<?php

namespace App\Http\Controllers;

use App\Models\Expo;
use App\Models\Penyelenggara;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class LabaRugiReportController extends Controller
{
    private function authorizeFinanceAccess(): void
    {
        $user = auth()->user();
        if (! $user || ! $user->hasAnyRole(['super_admin', 'admin', 'swe'])) {
            abort(403, 'Anda tidak memiliki akses ke laporan keuangan.');
        }
    }

    private function getPdf($record, $isPreview = false)
    {
        $record->load([
            'partisipasis.vendor',
            'partisipasis.dataPembayarans',
            'sponsors',
            'pengeluarans',
        ]);

        return Pdf::loadView('pdf.laba-rugi', [
            'expos' => [$record],
            'penyelenggara' => Penyelenggara::first(),
            'is_preview' => $isPreview,
        ])->setPaper('a4', 'portrait');
    }

    public function stream(Expo $record)
    {
        $this->authorizeFinanceAccess();

        $pdf = $this->getPdf($record, true);
        $filename = 'Laporan-Laba-Rugi-'.Str::slug($record->nama_expo).'.pdf';

        return $pdf->stream($filename);
    }

    public function download(Expo $record)
    {
        $this->authorizeFinanceAccess();

        $pdf = $this->getPdf($record, false);
        $filename = 'Laporan-Laba-Rugi-'.Str::slug($record->nama_expo).'.pdf';

        return $pdf->download($filename);
    }
}
