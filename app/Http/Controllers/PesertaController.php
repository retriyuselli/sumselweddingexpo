<?php

namespace App\Http\Controllers;

use App\Models\Partisipasi;
use App\Models\TenantSpot;
use App\Models\Vendor;
use App\Services\ExpoResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ExpoResolver $expoResolver)
    {
        $expo = $expoResolver->nearestActive();

        $pesertas = collect();
        $boothMap = collect();
        $categoryTenants = collect();
        $tenantSpots = collect();
        $search = trim((string) $request->input('search', ''));

        if ($expo) {
            $partisipasis = Partisipasi::query()
                ->active()
                ->where('expo_id', $expo->id)
                ->with(['vendor.jenisUsaha', 'categoryTenant', 'tenantSpot'])
                ->get()
                ->filter(fn (Partisipasi $p) => (bool) $p->is_active && $p->vendor);

            $pesertas = $this->buildPesertaEntries($partisipasis);

            if ($search !== '') {
                $needle = mb_strtolower($search);
                $pesertas = $pesertas->filter(function (array $entry) use ($needle) {
                    $vendor = $entry['vendor'];

                    return str_contains(mb_strtolower((string) $vendor->nama_vendor), $needle)
                        || str_contains(mb_strtolower((string) $vendor->kota), $needle);
                })->values();
            }

            $pesertas = $pesertas
                ->sortBy(fn (array $entry) => mb_strtolower((string) $entry['vendor']->nama_vendor))
                ->values();

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

        // Back-compat for view count variable name
        $partisipasis = $pesertas;

        return view('peserta.index', compact(
            'expo', 'pesertas', 'partisipasis', 'search', 'boothMap', 'categoryTenants', 'tenantSpots'
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
        $isPendamping = false;

        if ($expo) {
            $partisipasi = Partisipasi::query()
                ->active()
                ->where('expo_id', $expo->id)
                ->where('vendor_id', $vendor->id)
                ->with(['categoryTenant', 'tenantSpot', 'vendor'])
                ->first();

            if (! $partisipasi) {
                $partisipasi = Partisipasi::query()
                    ->active()
                    ->where('expo_id', $expo->id)
                    ->where(function ($q) use ($vendor) {
                        $q->whereJsonContains('vendor_pendamping', $vendor->id)
                            ->orWhereJsonContains('vendor_pendamping', (string) $vendor->id);
                    })
                    ->with(['categoryTenant', 'tenantSpot', 'vendor'])
                    ->first();

                $isPendamping = $partisipasi !== null;
            }
        }

        if (! $partisipasi) {
            abort(404);
        }

        return view('peserta.show', compact('vendor', 'expo', 'partisipasi', 'isPendamping'));
    }

    /**
     * Expand active partisipasis into public cards: vendor utama + vendor pendamping.
     *
     * @param  Collection<int, Partisipasi>  $partisipasis
     * @return Collection<int, array{vendor: Vendor, partisipasi: Partisipasi, is_pendamping: bool, host_vendor: ?Vendor}>
     */
    private function buildPesertaEntries(Collection $partisipasis): Collection
    {
        $pendampingIds = $partisipasis
            ->flatMap(fn (Partisipasi $p) => is_array($p->vendor_pendamping) ? $p->vendor_pendamping : [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $pendampingVendors = $pendampingIds->isEmpty()
            ? collect()
            : Vendor::query()
                ->with('jenisUsaha')
                ->whereIn('id', $pendampingIds)
                ->get()
                ->keyBy('id');

        $entries = collect();
        $seenVendorIds = [];

        foreach ($partisipasis as $partisipasi) {
            $main = $partisipasi->vendor;
            if ($main && ! isset($seenVendorIds[$main->id])) {
                $seenVendorIds[$main->id] = true;
                $entries->push([
                    'vendor' => $main,
                    'partisipasi' => $partisipasi,
                    'is_pendamping' => false,
                    'host_vendor' => null,
                ]);
            }

            $ids = is_array($partisipasi->vendor_pendamping) ? $partisipasi->vendor_pendamping : [];
            foreach ($ids as $rawId) {
                $id = (int) $rawId;
                if ($id <= 0 || isset($seenVendorIds[$id])) {
                    continue;
                }

                $pendamping = $pendampingVendors->get($id);
                if (! $pendamping) {
                    continue;
                }

                $seenVendorIds[$id] = true;
                $entries->push([
                    'vendor' => $pendamping,
                    'partisipasi' => $partisipasi,
                    'is_pendamping' => true,
                    'host_vendor' => $main,
                ]);
            }
        }

        return $entries;
    }
}
