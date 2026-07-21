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
            $query = Partisipasi::with(['vendor.jenisUsaha', 'categoryTenant', 'tenantSpot'])
                ->where('expo_id', $expo->id)
                ->whereHas('vendor', function ($q) use ($search) {
                    if ($search) {
                        $q->where('nama_vendor', 'like', "%{$search}%")
                            ->orWhere('kota', 'like', "%{$search}%");
                    }
                });

            $partisipasis = $query->get()
                ->sortBy(function ($partisipasi) {
                    return $partisipasi->vendor->nama_vendor;
                });

            // Lean booth map: only fields needed for floor plan
            $boothMap = Partisipasi::query()
                ->where('expo_id', $expo->id)
                ->whereNotNull('tenant_spot_id')
                ->with([
                    'vendor:id,nama_vendor,slug',
                    'categoryTenant:id,category',
                    'tenantSpot:id,kode_booth',
                ])
                ->get()
                ->filter(fn ($p) => $p->tenantSpot)
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
            $partisipasi = Partisipasi::where('vendor_id', $vendor->id)
                ->where('expo_id', $expo->id)
                ->with(['categoryTenant', 'tenantSpot'])
                ->first();
        }

        return view('peserta.show', compact('vendor', 'expo', 'partisipasi'));
    }
}
