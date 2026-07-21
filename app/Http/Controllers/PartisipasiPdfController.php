<?php

namespace App\Http\Controllers;

use App\Models\Expo;
use App\Models\Penyelenggara;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartisipasiPdfController extends Controller
{
    public function download(Request $request, Expo $expo)
    {
        $user = auth()->user();
        if (! $user || ! $user->hasAnyRole(['super_admin', 'admin', 'swe'])) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh daftar partisipasi.');
        }

        $onlyActive = $request->boolean('only_active', false);

        $partisipasis = $expo->partisipasis()
            ->with(['vendor.jenisUsaha', 'categoryTenant', 'tenantSpot'])
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('id')
            ->get();

        $filename = 'Daftar-Partisipasi-'.Str::slug($expo->nama_expo).'-'.Str::slug((string) $expo->periode).'.pdf';

        $pdf = Pdf::loadView('pdf.partisipasi', [
            'expo' => $expo,
            'partisipasis' => $partisipasis,
            'penyelenggara' => Penyelenggara::first(),
            'onlyActive' => $onlyActive,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
