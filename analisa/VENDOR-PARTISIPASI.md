# Analisa Domain — Vendor vs Partisipasi

> Tanggal: **21 Juli 2026** (diperbarui setelah refactor)  
> Path: `app/Models/Vendor.php`, `app/Models/Partisipasi.php`  
> Canvas: [sumselweddingexpo-vendor-partisipasi](/Users/ramadhonautama/.cursor/projects/Applications-MAMP-htdocs/canvases/sumselweddingexpo-vendor-partisipasi.canvas.tsx)

---

## Verdict

Pemisahan domain sudah diterapkan.

| Model | Peran |
|-------|--------|
| **Vendor** | Identitas bisnis permanen saja (brand, kontak, jenis usaha, produk, `nama_pendaftar`) |
| **Partisipasi** | Booking vendor di **satu expo** (paket, booth, harga, pembayaran, keterangan pendamping) |

---

## Field yang dipindah dari Vendor → Partisipasi

| Dulu di Vendor | Sekarang di Partisipasi |
|----------------|-------------------------|
| `paket` | `category_tenant_id` → `CategoryTenant` |
| `harga_jual` | `harga_jual` (+ `diskon`, `harga_bersih`) |
| `lokasi_booth` | `blok_tenant` (preferensi) / `tenant_spot_id` (assign final) |
| `pendamping_tenant` | dicatat di `keterangan` saat daftar exhibitor |

Kolom tersebut di-drop dari tabel `vendors` via migrasi `2026_07_21_220000_move_expo_fields_from_vendors_to_partisipasis` (dengan backfill ke Partisipasi expo aktif bila memungkinkan).

---

## Field yang benar di masing-masing

### Vendor (identity)
`user_id`, `nama_pendaftar`, `nama_vendor`, `slug`, `logo`, `jenis_usaha_id`, `alamat`, `kota`, `no_telepon`, `email`, `nama_pic`, `no_wa_pic`

### Partisipasi (per-expo)
`expo_id`, `vendor_id`, `tanggal_booking`, `category_tenant_id`, `tenant_spot_id`, `blok_tenant`, `harga_jual`, `diskon`, `harga_bersih`, `total_pembayaran`, `sisa_pembayaran`, `status_pembayaran`, barter fields, `keterangan`, `vendor_pendamping`

---

## Alur yang sudah diperbaiki

1. **Exhibitor register** → `ExhibitorRegistrationService` membuat `Vendor` + draft `Partisipasi` untuk expo aktif.
2. **Multi-expo** → vendor existing bisa join expo baru (form preferensi saja); gate = Partisipasi expo aktif, bukan “punya Vendor”.
3. Gagal buat Partisipasi → `ValidationException` (tidak ada flash sukses palsu).
4. Harga Partisipasi selalu dari katalog `CategoryTenant` (bukan input client).
5. Partners & janji temu hanya vendor yang punya Partisipasi di expo aktif.
6. Filament: filter status bayar + status CategoryTenant (`Aktif`/`Tidak Aktif`) diselaraskan; `vendor_pendamping` = array ID vendor (legacy string dinormalisasi ke `keterangan`).
7. Public views baca booth & paket dari Partisipasi.

---

## Model target (current)

```text
User (customer)
  └── Vendor (brand, kontak, jenis usaha, produk)
        └── Partisipasi × Expo
              ├── CategoryTenant  (paket + harga katalog)
              └── TenantSpot      (booth assigned)
```

---

*Dokumen ini melengkapi `analisa/audit.md` untuk domain modeling Vendor/Partisipasi.*
