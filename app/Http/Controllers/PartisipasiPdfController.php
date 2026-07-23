<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Partisipasis\PartisipasiResource;
use App\Models\Expo;
use App\Models\Partisipasi;
use App\Models\Penyelenggara;
use App\Models\RekeningTujuan;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartisipasiPdfController extends Controller
{
    public function download(Request $request, Expo $expo): Response|View
    {
        $this->authorizeAccess('Anda tidak memiliki akses untuk mengunduh daftar partisipasi.');

        $onlyActive = $request->boolean('only_active', false);

        if (! $request->boolean('download') && ! $request->boolean('raw')) {
            return view('pdf.viewer', [
                'title' => 'Daftar Partisipasi — '.$expo->nama_expo,
                'subtitle' => trim(implode(' · ', array_filter([
                    $expo->periode,
                    $expo->tanggal_mulai?->format('d M Y'),
                    $onlyActive ? 'Hanya aktif' : 'Semua partisipasi',
                ]))),
                'backUrl' => PartisipasiResource::getUrl('index'),
                'downloadUrl' => route('partisipasis.pdf', [
                    'expo' => $expo,
                    'only_active' => $onlyActive ? 1 : 0,
                    'download' => 1,
                ]),
                'pdfUrl' => route('partisipasis.pdf', [
                    'expo' => $expo,
                    'only_active' => $onlyActive ? 1 : 0,
                    'raw' => 1,
                ]),
            ]);
        }

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
            'generatedAt' => now('Asia/Jakarta'),
        ])->setPaper('a4', 'landscape');

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    public function invoice(Request $request, Partisipasi $partisipasi): Response|View
    {
        $this->authorizeAccess('Anda tidak memiliki akses untuk mengunduh invoice partisipasi.');

        if (! $request->boolean('download') && ! $request->boolean('raw')) {
            $partisipasi->loadMissing(['vendor', 'expo']);

            return view('pdf.viewer', [
                'title' => 'Invoice Partisipasi',
                'subtitle' => trim(implode(' · ', array_filter([
                    $partisipasi->vendor?->nama_vendor,
                    $partisipasi->expo?->nama_expo,
                ]))),
                'backUrl' => PartisipasiResource::getUrl('index'),
                'downloadUrl' => route('partisipasis.invoice', [
                    'partisipasi' => $partisipasi,
                    'download' => 1,
                ]),
                'pdfUrl' => route('partisipasis.invoice', [
                    'partisipasi' => $partisipasi,
                    'raw' => 1,
                ]),
            ]);
        }

        $partisipasi->load([
            'expo',
            'vendor.jenisUsaha',
            'categoryTenant',
            'tenantSpot',
            'dataPembayarans' => fn ($q) => $q->orderBy('tanggal_bayar')->orderBy('id'),
            'dataPembayarans.rekeningTujuan',
        ]);

        $penyelenggara = Penyelenggara::first();
        $rekeningTujuans = RekeningTujuan::query()
            ->orderBy('nama_bank')
            ->orderBy('nama_pemilik')
            ->get();
        $generatedAt = now('Asia/Jakarta');
        $invoiceNumber = sprintf('INV-%s-%05d', $generatedAt->format('Y'), $partisipasi->id);
        $filename = 'Invoice-'.$invoiceNumber.'.pdf';

        $pdf = Pdf::loadView('pdf.partisipasi-invoice', [
            'partisipasi' => $partisipasi,
            'expo' => $partisipasi->expo,
            'vendor' => $partisipasi->vendor,
            'penyelenggara' => $penyelenggara,
            'rekeningTujuans' => $rekeningTujuans,
            'invoiceNumber' => $invoiceNumber,
            'logoBase64' => $this->localImageDataUri($penyelenggara?->logo),
            'generatedAt' => $generatedAt,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('defaultFont', 'DejaVu Sans');

        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

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
