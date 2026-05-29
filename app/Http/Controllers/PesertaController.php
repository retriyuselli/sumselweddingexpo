<?php

namespace App\Http\Controllers;

use App\Models\Expo;
use App\Models\Partisipasi;
use App\Models\TenantSpot;
use App\Models\Vendor;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cari expo terdekat yang aktif
        $expo = Expo::where('status', true)
            ->whereDate('tanggal_mulai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        // Jika tidak ada expo yang akan datang, ambil expo terakhir yang aktif
        if (!$expo) {
            $expo = Expo::where('status', true)
                ->orderBy('tanggal_mulai', 'desc')
                ->first();
        }

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

            // Unfiltered booth map for floor plan (always shows full layout)
            $allPartisipasis = Partisipasi::with(['vendor', 'categoryTenant', 'tenantSpot'])
                ->where('expo_id', $expo->id)
                ->get();
            $boothMap = $allPartisipasis
                ->filter(fn ($p) => $p->tenantSpot)
                ->keyBy(fn ($p) => $p->tenantSpot->kode_booth);

            $categoryTenants = $expo->categoryTenants()->get();

            // Venue spot definitions (from DB if configured, else empty = use hardcoded fallback)
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
    public function show($slug)
    {
        $vendor = Vendor::where('slug', $slug)
            ->with(['jenisUsaha', 'products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->firstOrFail();

        // Cari expo terdekat yang aktif
        $expo = Expo::where('status', true)
            ->whereDate('tanggal_mulai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        if (!$expo) {
            $expo = Expo::where('status', true)
                ->orderBy('tanggal_mulai', 'desc')
                ->first();
        }

        $partisipasi = null;
        if ($expo) {
            $partisipasi = Partisipasi::where('vendor_id', $vendor->id)
                ->where('expo_id', $expo->id)
                ->with('categoryTenant')
                ->first();
        }

        return view('peserta.show', compact('vendor', 'expo', 'partisipasi'));
    }
}
