<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Doorprizes\DoorprizeResource;
use App\Models\Doorprize;
use App\Models\Expo;
use App\Models\Penyelenggara;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DoorprizeReportController extends Controller
{
    public function download(Request $request, Expo $expo): Response|View
    {
        $this->authorizeAccess('Anda tidak memiliki akses untuk mengunduh laporan doorprize.');

        if (! $request->boolean('download') && ! $request->boolean('raw')) {
            return view('pdf.viewer', [
                'title' => 'Laporan Doorprize — '.$expo->nama_expo,
                'subtitle' => $expo->labelDetails() ?? 'Semua pemenang undian',
                'backUrl' => DoorprizeResource::getUrl('index'),
                'downloadUrl' => route('doorprizes.laporan', [
                    'expo' => $expo,
                    'download' => 1,
                ]),
                'pdfUrl' => route('doorprizes.laporan', [
                    'expo' => $expo,
                    'raw' => 1,
                ]),
            ]);
        }

        $doorprizes = Doorprize::query()
            ->whereHas('partisipasi', fn ($q) => $q->where('expo_id', $expo->id))
            ->with(['partisipasi.vendor'])
            ->orderBy('name')
            ->get();

        $filename = 'Laporan-Doorprize-'.Str::slug($expo->nama_expo)
            .($expo->periode ? '-'.Str::slug((string) $expo->periode) : '')
            .'.pdf';

        $pdf = Pdf::loadView('pdf.doorprize', [
            'expo' => $expo,
            'doorprizes' => $doorprizes,
            'penyelenggara' => Penyelenggara::first(),
            'generatedAt' => now('Asia/Jakarta'),
        ])->setPaper('a4', 'landscape');

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    private function authorizeAccess(string $message): void
    {
        $user = auth()->user();
        if (! $user || ! $user->hasAnyRole(['super_admin', 'admin', 'swe'])) {
            abort(403, $message);
        }
    }
}
