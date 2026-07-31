<?php

namespace App\Http\Controllers;

use App\Enums\CategoryTier;
use App\Http\Requests\StoreProductVendorRequest;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateProductVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Http\Resources\VendorResource;
use App\Models\Appointment;
use App\Models\JenisUsaha;
use App\Models\ProductVendor;
use App\Models\Vendor;
use App\Services\ExhibitorRegistrationService;
use App\Services\ExpoResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function partners(ExpoResolver $expoResolver)
    {
        $expo = $expoResolver->nearestActive();

        $vendors = Vendor::query()
            ->when($expo, function ($q) use ($expo) {
                $q->whereHas('partisipasis', fn ($p) => $p->where('expo_id', $expo->id)->where('is_active', true));
            }, function ($q) {
                $q->whereRaw('0 = 1');
            })
            ->with([
                'jenisUsaha',
                'partisipasis' => function ($q) use ($expo) {
                    $q->with(['categoryTenant', 'tenantSpot'])
                        ->when($expo, fn ($qq) => $qq->where('expo_id', $expo->id)->where('is_active', true))
                        ->latest('id');
                },
            ])
            ->withCount([
                'products as products_active_count' => function ($q) {
                    $q->where('is_active', true);
                },
            ])
            ->latest()
            ->get();

        $jenisUsahas = JenisUsaha::query()
            ->withCount(['vendors as vendors_count' => function ($q) use ($expo) {
                if ($expo) {
                    $q->whereHas('partisipasis', fn ($p) => $p->where('expo_id', $expo->id)->where('is_active', true));
                } else {
                    $q->whereRaw('0 = 1');
                }
            }])
            ->orderBy('nama_jenis_usaha')
            ->get();

        return view('partners', compact('vendors', 'jenisUsahas', 'expo'));
    }

    public function index()
    {
        $this->authorize('viewAny', Vendor::class);

        $query = Vendor::with('jenisUsaha')->latest();

        if (! Auth::user()->can('ViewAny:Vendor') && ! Auth::user()->hasAnyRole(['super_admin', 'admin', 'swe'])) {
            $query->where('user_id', Auth::id());
        }

        $vendors = $query->paginate(10);

        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        $this->authorize('create', Vendor::class);

        return redirect()->route('exhibitor')
            ->with('info', 'Pendaftaran vendor publik dilakukan lewat halaman Exhibitor.');
    }

    public function store(StoreVendorRequest $request, ExhibitorRegistrationService $registration)
    {
        if ($request->routeIs('vendors.store') && $request->filled('user_id')) {
            $targetUserId = (int) $request->input('user_id');
            $alreadyRegistered = Vendor::where('user_id', $targetUserId)->exists();
            if ($alreadyRegistered) {
                return redirect()->back()
                    ->withErrors(['user_id' => 'User tersebut sudah memiliki vendor.'])
                    ->withInput();
            }
        }

        if ($request->routeIs('exhibitor.store')) {
            $existingVendor = $request->existingVendorForUser();
            $participation = $request->participationAttributes();

            if ($existingVendor) {
                $registration->joinExpo($existingVendor, $participation);

                return redirect()->route('exhibitor')
                    ->with('success', 'Pendaftaran keikutsertaan expo berhasil dikirim! Kami akan segera menghubungi Anda.');
            }

            $data = $request->vendorAttributes();
            $data['slug'] = Str::slug($data['nama_vendor'] ?? '');
            $registration->register($data, $participation, Auth::id());

            return redirect()->route('exhibitor')
                ->with('success', 'Pendaftaran exhibitor berhasil dikirim! Kami akan segera menghubungi Anda.');
        }

        $data = $request->vendorAttributes();
        $data['slug'] = Str::slug($data['nama_vendor'] ?? '');
        if ($request->filled('user_id')) {
            $data['user_id'] = (int) $request->input('user_id');
        }
        Vendor::create($data);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function show(Vendor $vendor, ExpoResolver $expoResolver)
    {
        $expo = $expoResolver->nearestActive();
        $vendor->load(['jenisUsaha', 'partisipasis' => function ($q) use ($expo) {
            $q->with(['categoryTenant', 'tenantSpot'])
                ->when($expo, fn ($qq) => $qq->where('expo_id', $expo->id)->where('is_active', true))
                ->latest('id');
        }]);
        $partisipasi = $vendor->partisipasis->first();

        $firstProduct = ProductVendor::where('vendor_id', $vendor->id)
            ->where('is_active', true)
            ->latest()
            ->first();
        $products = ProductVendor::where('vendor_id', $vendor->id)
            ->where('is_active', true)
            ->latest()
            ->take(24)
            ->get();
        $upcomingAppointments = Appointment::with('customer:id,name')
            ->where('vendor_id', $vendor->id)
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at', 'asc')
            ->paginate(10);

        return view('vendors.show', compact('vendor', 'upcomingAppointments', 'firstProduct', 'products', 'partisipasi', 'expo'));
    }

    public function edit(Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        return redirect()->route('vendors.show', $vendor->slug)
            ->with('info', 'Edit data vendor tersedia di panel admin (Partisipasi Expo untuk booth/paket).');
    }

    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        $payload = $request->validated();
        $payload['slug'] = Str::slug($payload['nama_vendor'] ?? $vendor->nama_vendor);
        $vendor->update($payload);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil diupdate!');
    }

    public function destroy(Vendor $vendor)
    {
        $this->authorize('delete', $vendor);

        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil dihapus!');
    }

    public function storeProduct(StoreProductVendorRequest $request, Vendor $vendor)
    {
        $data = $request->validated();
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('product_photos', 'public');
        }
        $slugBase = Str::slug($data['nama_produk']);
        $slug = $slugBase;
        $i = 1;
        while (ProductVendor::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$i;
            $i++;
        }

        ProductVendor::create([
            'vendor_id' => $vendor->id,
            'nama_produk' => $data['nama_produk'],
            'slug' => $slug,
            'harga' => (int) ($data['harga'] ?? 0),
            'dp_fixed' => (int) ($data['dp_fixed'] ?? 0),
            'deskripsi' => $data['deskripsi'] ?? null,
            'foto_url' => $fotoPath ?? null,
            'stok' => (int) ($data['stok'] ?? 0),
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ]);

        return redirect()->route('vendors.show', $vendor->slug)->with('success', 'Produk berhasil ditambahkan!');
    }

    public function updateProduct(UpdateProductVendorRequest $request, Vendor $vendor, ProductVendor $productVendor)
    {
        $data = $request->validated();
        $update = [
            'nama_produk' => $data['nama_produk'],
            'harga' => (int) ($data['harga'] ?? 0),
            'dp_fixed' => (int) ($data['dp_fixed'] ?? 0),
            'deskripsi' => $data['deskripsi'] ?? null,
            'stok' => (int) ($data['stok'] ?? 0),
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : ($productVendor->is_active ?? true),
        ];

        if ($request->hasFile('foto')) {
            $update['foto_url'] = $request->file('foto')->store('product_photos', 'public');
        }

        $productVendor->update($update);

        return redirect()->route('vendors.show', $vendor->slug)->with('success', 'Produk berhasil diupdate!');
    }

    public function editProduct(Vendor $vendor, ProductVendor $productVendor)
    {
        if (! Auth::check() || (int) $vendor->user_id !== (int) Auth::id()) {
            abort(403);
        }
        if ((int) $productVendor->vendor_id !== (int) $vendor->id) {
            abort(404);
        }

        return view('vendors.products.edit', [
            'vendor' => $vendor,
            'product' => $productVendor,
        ]);
    }

    public function getAllVendors()
    {
        $this->authorize('viewAny', Vendor::class);

        $vendors = Vendor::query()
            ->with('jenisUsaha:id,nama_jenis_usaha')
            ->select(['id', 'nama_vendor', 'slug', 'jenis_usaha_id', 'kota', 'logo'])
            ->latest()
            ->paginate(50);

        return VendorResource::collection($vendors);
    }

    public function search(Request $request)
    {
        $this->authorize('viewAny', Vendor::class);

        $query = $request->get('query');

        $vendors = Vendor::with('jenisUsaha')
            ->where(function ($q) use ($query) {
                $q->where('nama_vendor', 'like', "%{$query}%")
                    ->orWhere('nama_pendaftar', 'like', "%{$query}%")
                    ->orWhereHas('jenisUsaha', function ($sub) use ($query) {
                        $sub->where('nama_jenis_usaha', 'like', "%{$query}%");
                    });
            })
            ->paginate(10);

        return view('vendors.index', compact('vendors', 'query'));
    }

    public function getByJenisUsaha($jenisUsahaId)
    {
        $this->authorize('viewAny', Vendor::class);

        $vendors = Vendor::where('jenis_usaha_id', $jenisUsahaId)
            ->with('jenisUsaha:id,nama_jenis_usaha')
            ->select(['id', 'nama_vendor', 'slug', 'jenis_usaha_id', 'kota', 'logo'])
            ->paginate(50);

        return VendorResource::collection($vendors);
    }

    public function exhibitorPage(ExpoResolver $expoResolver)
    {
        $vendors = Vendor::with('jenisUsaha')->latest()->get();
        $jenisUsahas = Cache::remember('jenis_usahas.with_vendor_counts', 600, function () {
            return JenisUsaha::withCount('vendors')->get();
        });
        $nearestExpo = $expoResolver->nearestActive();
        $currentVendor = null;
        $currentPartisipasi = null;

        if (Auth::check()) {
            $currentVendor = Vendor::with('jenisUsaha')
                ->where('user_id', Auth::id())
                ->latest()
                ->first();
            if ($currentVendor) {
                $currentPartisipasi = $currentVendor->partisipasiForExpo($nearestExpo?->id);
            }
        }

        $registeredForCurrentExpo = $currentPartisipasi !== null;
        $hasVendorIdentity = $currentVendor !== null;
        // Back-compat for views/dashboard that still expect this name = has vendor identity
        $registeredAsExhibitor = $hasVendorIdentity;

        $paketOptions = [];
        $paketPrices = [];
        if ($nearestExpo) {
            $rows = Cache::remember("category_tenants.paket.{$nearestExpo->id}", 300, function () use ($nearestExpo) {
                return DB::table('category_tenants')
                    ->where('expo_id', $nearestExpo->id)
                    ->where('status', 'Aktif')
                    ->select('category', 'harga_jual')
                    ->get();
            });

            foreach ($rows as $row) {
                $value = (string) $row->category;
                $label = CategoryTier::tryFrom($value)?->label() ?? $value;
                if (! array_key_exists($value, $paketOptions)) {
                    $paketOptions[$value] = $label;
                }
                if (! array_key_exists($value, $paketPrices)) {
                    $paketPrices[$value] = (int) ($row->harga_jual ?? 0);
                }
            }
        }

        return view('exhibitor', compact(
            'vendors',
            'jenisUsahas',
            'registeredAsExhibitor',
            'registeredForCurrentExpo',
            'hasVendorIdentity',
            'paketOptions',
            'paketPrices',
            'nearestExpo',
            'currentVendor',
            'currentPartisipasi'
        ));
    }

    public function showRegistrationForm()
    {
        return redirect()->route('exhibitor');
    }
}
