<?php

namespace App\Http\Controllers;

use App\Models\Partisipasi;
use App\Models\TenantSpot;
use App\Models\Vendor;
use App\Services\ExpoResolver;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ExpoResolver $expoResolver)
    {
        $expo = $expoResolver->nearestActive();

        $partisipasis = collect();
        $boothMap = collect();
        $categoryTenants = collect();
        $tenantSpots = collect();
        $search = $request->input('search');

        if ($expo) {
            // Publik: hanya partisipasi aktif
            $query = Partisipasi::query()
                ->active()
                ->where('expo_id', $expo->id)
                ->with(['vendor.jenisUsaha', 'categoryTenant', 'tenantSpot'])
                ->whereHas('vendor', function ($q) use ($search) {
                    if ($search) {
                        $q->where('nama_vendor', 'like', "%{$search}%")
                            ->orWhere('kota', 'like', "%{$search}%");
                    }
                });

            $partisipasis = $query->get()
                ->filter(fn (Partisipasi $p) => (bool) $p->is_active && $p->vendor)
                ->sortBy(fn (Partisipasi $p) => $p->vendor->nama_vendor)
                ->values();

            // Floor plan: booth hanya diisi partisipasi aktif
            $boothMap = Partisipasi::query()
                ->active()
                ->where('expo_id', $expo->id)
                ->whereNotNull('tenant_spot_id')
                ->with([
                    'vendor:id,nama_vendor,slug',
                    'categoryTenant:id,category',
                    'tenantSpot:id,kode_booth',
                ])
                ->get()
                ->filter(fn ($p) => (bool) $p->is_active && $p->tenantSpot)
                ->keyBy(fn ($p) => $p->tenantSpot->kode_booth);

            $categoryTenants = $expo->categoryTenants()->get();

            $tenantSpots = TenantSpot::where('expo_id', $expo->id)
                ->orderBy('blok')
                ->orderBy('section')
                ->orderBy('baris')
                ->orderBy('kolom')
                ->get();
        }

        return view('peserta.index', compact(
            'expo', 'partisipasis', 'search', 'boothMap', 'categoryTenants', 'tenantSpots'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show($slug, ExpoResolver $expoResolver)
    {
        $vendor = Vendor::where('slug', $slug)
            ->with(['jenisUsaha', 'products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->firstOrFail();

        $expo = $expoResolver->nearestActive();

        $partisipasi = null;
        if ($expo) {
            $partisipasi = Partisipasi::query()
                ->active()
                ->where('vendor_id', $vendor->id)
                ->where('expo_id', $expo->id)
                ->with(['categoryTenant', 'tenantSpot'])
                ->first();
        }

        // Vendor tanpa partisipasi aktif di expo berjalan tidak ditampilkan sebagai peserta
        if (! $partisipasi) {
            abort(404);
        }

        return view('peserta.show', compact('vendor', 'expo', 'partisipasi'));
    }
}
