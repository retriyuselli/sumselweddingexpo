<?php

namespace App\Http\Controllers;

use App\Models\Expo;
use App\Models\Partisipasi;
use App\Models\Penyelenggara;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartisipasiPdfController extends Controller
{
    public function download(Request $request, Expo $expo)
    {
        $this->authorizeAccess('Anda tidak memiliki akses untuk mengunduh daftar partisipasi.');

        $onlyActive = $request->boolean('only_active', false);

        $partisipasis = $expo->partisipasis()
            ->with(['vendor.jenisUsaha', 'categoryTenant', 'tenantSpot'])
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('id')
            ->get();

        $pendampingIds = $partisipasis
            ->flatMap(function ($p) {
                $raw = $p->vendor_pendamping;

                return is_array($raw) ? $raw : [];
            })
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $pendampingNames = $pendampingIds === []
            ? collect()
            : Vendor::query()->whereIn('id', $pendampingIds)->pluck('nama_vendor', 'id');

        $filename = 'Daftar-Partisipasi-'.Str::slug($expo->nama_expo).'-'.Str::slug((string) $expo->periode).'.pdf';

        $pdf = Pdf::loadView('pdf.partisipasi', [
            'expo' => $expo,
            'partisipasis' => $partisipasis,
            'penyelenggara' => Penyelenggara::first(),
            'onlyActive' => $onlyActive,
            'pendampingNames' => $pendampingNames,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function invoice(Request $request, Partisipasi $partisipasi): Response
    {
        $this->authorizeAccess('Anda tidak memiliki akses untuk mengunduh invoice partisipasi.');

        $partisipasi->load([
            'expo',
            'vendor.jenisUsaha',
            'categoryTenant',
            'tenantSpot',
            'dataPembayarans' => fn ($q) => $q->orderBy('tanggal_bayar')->orderBy('id'),
        ]);

        $penyelenggara = Penyelenggara::first();
        $invoiceNumber = sprintf('INV-%s-%05d', now()->format('Y'), $partisipasi->id);
        $filename = 'Invoice-'.$invoiceNumber.'.pdf';

        $pdf = Pdf::loadView('pdf.partisipasi-invoice', [
            'partisipasi' => $partisipasi,
            'expo' => $partisipasi->expo,
            'vendor' => $partisipasi->vendor,
            'penyelenggara' => $penyelenggara,
            'invoiceNumber' => $invoiceNumber,
            'logoBase64' => $this->localImageDataUri($penyelenggara?->logo),
            'generatedAt' => now(),
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans');

        $disposition = $request->boolean('download', true) ? 'attachment' : 'inline';

        $response = response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition.'; filename="'.$filename.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->headers->addCacheControlDirective('no-store');

        return $response;
    }

    private function authorizeAccess(string $message): void
    {
        $user = auth()->user();
        if (! $user || ! $user->hasAnyRole(['super_admin', 'admin', 'swe'])) {
            abort(403, $message);
        }
    }

    private function localImageDataUri(?string $path): ?string
    {
        if (blank($path)
            || Str::startsWith($path, ['http://', 'https://'])
            || str_contains($path, '..')) {
            return null;
        }

        $path = ltrim($path, '/');
        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        $mime = Storage::disk('public')->mimeType($path);
        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'image/gif'], true)) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode(Storage::disk('public')->get($path));
    }
}
