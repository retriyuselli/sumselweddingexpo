<?php

namespace App\Http\Controllers;

use App\Enums\CategoryTier;
use App\Models\CategoryTenant;
use App\Models\Expo;
use App\Models\JenisUsaha;
use App\Models\Vendor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    /**
     * Display the partners page (public).
     */
    public function partners()
    {
        $vendors = Vendor::with('jenisUsaha')
            ->withCount([
                'products as products_active_count' => function ($q) {
                    $q->where('is_active', true);
                },
            ])
            ->latest()
            ->get();
        $jenisUsahas = JenisUsaha::withCount('vendors')->orderBy('nama_jenis_usaha')->get();

        return view('partners', compact('vendors', 'jenisUsahas'));
    }

    /**
     * Display a listing of the vendors.
     */
    public function index()
    {
        $vendors = Vendor::with('jenisUsaha')->latest()->paginate(10);

        return view('vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        $jenisUsahas = JenisUsaha::all();

        return view('vendors.create', compact('jenisUsahas'));
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        // Batasi satu user hanya untuk satu vendor
        if ($request->routeIs('exhibitor.store') && Auth::check()) {
            $alreadyRegistered = Vendor::where('user_id', Auth::id())->exists();
            if ($alreadyRegistered) {
                return redirect()->route('exhibitor')
                    ->with('error', 'Anda sudah terdaftar sebagai exhibitor. Satu akun hanya bisa mendaftarkan satu vendor.');
            }
        } elseif ($request->routeIs('vendors.store') && $request->filled('user_id')) {
            $targetUserId = (int) $request->input('user_id');
            $alreadyRegistered = Vendor::where('user_id', $targetUserId)->exists();
            if ($alreadyRegistered) {
                return redirect()->back()
                    ->withErrors(['user_id' => 'User tersebut sudah memiliki vendor.'])
                    ->withInput();
            }
        }

        $validator = Validator::make($request->all(), [
            'nama_pendaftar' => 'required|string|max:255',
            'nama_vendor' => 'required|string|max:255',
            'jenis_usaha_id' => 'required|exists:jenis_usahas,id',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'pendamping_tenant' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email',
            'nama_pic' => 'required|string|max:255',
            'no_wa_pic' => 'required|string|max:20',
            'paket' => ['required', Rule::enum(CategoryTier::class)],
            'lokasi_booth' => 'nullable|string|max:100',
            'harga_jual' => 'nullable|integer|min:0',
            'user_id' => 'nullable|integer|exists:users,id|unique:vendors,user_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'nama_pendaftar',
            'nama_vendor',
            'jenis_usaha_id',
            'alamat',
            'kota',
            'no_telepon',
            'pendamping_tenant',
            'email',
            'nama_pic',
            'no_wa_pic',
            'paket',
            'lokasi_booth',
            'harga_jual',
        ]);

        $data['slug'] = Str::slug($data['nama_vendor'] ?? '');

        if ($request->routeIs('exhibitor.store') && Auth::check()) {
            if ($request->filled('user_id') && (int) $request->input('user_id') !== Auth::id()) {
                return redirect()->back()
                    ->withErrors(['user_id' => 'User ID tidak sesuai dengan akun yang login.'])
                    ->withInput();
            }

            $data['user_id'] = Auth::id();
        }

        Vendor::create($data);

        if ($request->routeIs('exhibitor.store')) {
            return redirect()->route('exhibitor')
                ->with('success', 'Pendaftaran exhibitor berhasil dikirim! Kami akan segera menghubungi Anda.');
        }

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil ditambahkan!');
    }

    /**
     * Display the specified vendor.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('jenisUsaha', 'partisipasis');
        $firstProduct = \App\Models\ProductVendor::where('vendor_id', $vendor->id)
            ->where('is_active', true)
            ->latest()
            ->first();
        $products = \App\Models\ProductVendor::where('vendor_id', $vendor->id)
            ->where('is_active', true)
            ->latest()
            ->take(24)
            ->get();
        $upcomingAppointments = Appointment::with('customer:id,name')
            ->where('vendor_id', $vendor->id)
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at', 'asc')
            ->paginate(10);

        return view('vendors.show', compact('vendor', 'upcomingAppointments', 'firstProduct', 'products'));
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(Vendor $vendor)
    {
        $jenisUsahas = JenisUsaha::all();

        return view('vendors.edit', compact('vendor', 'jenisUsahas'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'nama_pendaftar' => 'required|string|max:255',
            'nama_vendor' => 'required|string|max:255',
            'jenis_usaha_id' => 'required|exists:jenis_usahas,id',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'pendamping_tenant' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'nama_pic' => 'required|string|max:255',
            'no_wa_pic' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $payload = $request->all();
        $payload['slug'] = Str::slug($payload['nama_vendor'] ?? $vendor->nama_vendor);
        $vendor->update($payload);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil diupdate!');
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor berhasil dihapus!');
    }

    public function storeProduct(Request $request, Vendor $vendor)
    {
        if (!Auth::check() || (int) $vendor->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'dp_fixed' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:1024',
            'stok' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('product_photos', 'public');
        }
        $slugBase = Str::slug($data['nama_produk']);
        $slug = $slugBase;
        $i = 1;
        while (\App\Models\ProductVendor::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$i;
            $i++;
        }

            \App\Models\ProductVendor::create([
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

    public function updateProduct(Request $request, Vendor $vendor, \App\Models\ProductVendor $productVendor)
    {
        if (!Auth::check() || (int) $vendor->user_id !== (int) Auth::id()) {
            abort(403);
        }
        if ((int) $productVendor->vendor_id !== (int) $vendor->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'dp_fixed' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:1024',
            'stok' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $update = [
            'nama_produk' => $data['nama_produk'],
            'harga' => (int) ($data['harga'] ?? 0),
            'dp_fixed' => (int) ($data['dp_fixed'] ?? 0),
            'deskripsi' => $data['deskripsi'] ?? null,
            'stok' => (int) ($data['stok'] ?? 0),
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : ($productVendor->is_active ?? true),
        ];

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('product_photos', 'public');
            $update['foto_url'] = $fotoPath;
        }

        $productVendor->update($update);

        return redirect()->route('vendors.show', $vendor->slug)->with('success', 'Produk berhasil diupdate!');
    }

    public function editProduct(Vendor $vendor, \App\Models\ProductVendor $productVendor)
    {
        if (!Auth::check() || (int) $vendor->user_id !== (int) Auth::id()) {
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

    /**
     * Get all vendors (for API or AJAX requests)
     */
    public function getAllVendors()
    {
        $vendors = Vendor::with('jenisUsaha')->get();

        return response()->json($vendors);
    }

    /**
     * Search vendors by name or business type
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        $vendors = Vendor::with('jenisUsaha')
            ->where('nama_vendor', 'like', "%{$query}%")
            ->orWhere('nama_pendaftar', 'like', "%{$query}%")
            ->orWhereHas('jenisUsaha', function ($q) use ($query) {
                $q->where('nama_jenis_usaha', 'like', "%{$query}%");
            })
            ->paginate(10);

        return view('vendors.index', compact('vendors', 'query'));
    }

    /**
     * Get vendors by business type (for filtering)
     */
    public function getByJenisUsaha($jenisUsahaId)
    {
        $vendors = Vendor::where('jenis_usaha_id', $jenisUsahaId)
            ->with('jenisUsaha')
            ->get();

        return response()->json($vendors);
    }

    /**
     * Display exhibitor page with list of vendors
     */
    public function exhibitorPage()
    {
        $vendors = Vendor::with('jenisUsaha')->latest()->get();
        $jenisUsahas = JenisUsaha::withCount('vendors')->get();
        $registeredAsExhibitor = Auth::check() ? Vendor::where('user_id', Auth::id())->exists() : false;
        $currentVendor = null;
        if ($registeredAsExhibitor) {
            $currentVendor = Vendor::with('jenisUsaha')
                ->where('user_id', Auth::id())
                ->latest()
                ->first();
        }

        // Tentukan Expo terdekat yang aktif
        $nearestExpo = Expo::where('status', true)
            ->whereDate('tanggal_mulai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();
        if (! $nearestExpo) {
            $nearestExpo = Expo::where('status', true)
                ->orderBy('tanggal_mulai', 'desc')
                ->first();
        }

        // Ambil opsi paket (kategori) dan harga dari CategoryTenant untuk expo terdekat
        $paketOptions = [];
        $paketPrices = [];
        if ($nearestExpo) {
            $rows = DB::table('category_tenants')
                ->where('expo_id', $nearestExpo->id)
                ->select('category', 'harga_jual')
                ->get();

            foreach ($rows as $row) {
                $value = (string) $row->category; // nilai mentah string
                $label = CategoryTier::tryFrom($value)?->label() ?? $value;
                if (! array_key_exists($value, $paketOptions)) {
                    $paketOptions[$value] = $label;
                }
                // Gunakan harga pertama yang ditemukan untuk kategori tsb
                if (! array_key_exists($value, $paketPrices)) {
                    $paketPrices[$value] = (int) ($row->harga_jual ?? 0);
                }
            }
        }

        return view('exhibitor', compact('vendors', 'jenisUsahas', 'registeredAsExhibitor', 'paketOptions', 'paketPrices', 'nearestExpo', 'currentVendor'));
    }

    /**
     * Show vendor registration form
     */
    public function showRegistrationForm()
    {
        // Alihkan ke halaman exhibitor utama yang memiliki form terintegrasi
        return redirect()->route('exhibitor');
    }
}
