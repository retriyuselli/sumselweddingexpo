# Audit Aplikasi — Sumsel Wedding Expo

> **Acuan keamanan & performa** — mulai 21 Juli 2026, dokumen ini jadi referensi hardening dan perbaikan teknis untuk `sumselweddingexpo`.
>
> | | |
> |---|---|
> | **Waktu analisa** | Selasa, **21 Juli 2026** (~16:48–19:30 WIB) |
> | **Path lokal** | `/Applications/MAMP/htdocs/sumselweddingexpo` |
> | **Domain production** | `sumselweddingexpo.com` |
> | **Metode** | Static code review (routes, controllers, Filament, seeders, migrations) + perbaikan P0/P1/P2 di sesi yang sama |
> | **Canvas ringkas** | `~/.cursor/projects/Applications-MAMP-htdocs/canvases/sumselweddingexpo-app-audit.canvas.tsx` |
> | **Link** | cd /Applications/MAMP/htdocs/sumselweddingexpo |

---

## Ringkasan verdict

**Admin Filament relatif matang** (Shield, panel gate, Breezy, activity log).  
**Frontend authenticated adalah titik lemah utama** sebelum perbaikan: IDOR vendor, mass assignment, stored XSS, payment IDOR, PDF keuangan terlalu terbuka, PII di disk public.

Setelah perbaikan P0–P2 (21 Juli 2026), permukaan kritis di atas **sudah ditutup di kode**. Beberapa langkah **opsional / manual** masih perlu dijalankan di environment (rotasi password lama, Redis production, migrasi file KTP).

| Area | Sebelum | Sesudah P0–P2 |
|------|---------|----------------|
| Keamanan frontend | Critical — belum production-ready | Critical items diperbaiki |
| Performa laporan keuangan | High (N× aggregate) | Aggregator grouped query |
| Maintainability payment routes | Fat closures di `web.php` | `PaymentController` + `PaymentStatusSync` |
| Tests hardening | Hampir kosong | 11 tests lulus (XSS, IDOR, webhook, ownership) |

---

## 1. Tech stack (saat analisa)

| Layer | Package | Versi / constraint |
|-------|---------|-------------------|
| PHP | — | `^8.2` |
| Backend | `laravel/framework` | `^13.0` |
| Admin | `filament/filament` | `~5.0` |
| AuthZ | `bezhansalleh/filament-shield` | `^4.0` |
| Profile/2FA | `jeffgreco13/filament-breezy` | `^3.0` |
| Payments | `midtrans/midtrans-php` | `^2.6` |
| PDF | `barryvdh/laravel-dompdf` | `^3.1` |
| Audit | `spatie/laravel-activitylog` | `^5.0` |
| Logs UI | `opcodesio/log-viewer` | `^3.24` |
| Frontend | Vite / Tailwind / axios | `^7` / `4.x` / `^1.11` |

**Fitur utama:** situs expo publik, registrasi exhibitor/vendor, produk & appointment, checkout Midtrans, admin Filament (partisipasi, keuangan, doorprize, blog, roles).

**Infra default:** `CACHE_STORE=database`, `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`.

---

## 2. Temuan keamanan

### Critical

| # | Temuan | Evidence | Dampak | Status |
|---|--------|----------|--------|--------|
| C1 | Vendor CRUD tanpa otorisasi (IDOR) | `routes/web.php` vendor group · `VendorController` | User login bisa edit/hapus vendor mana saja | **Diperbaiki** — `VendorPolicy` + `authorize` |
| C2 | Mass assignment `$request->all()` | `VendorController::update` · `Vendor::$fillable` | Hijack `user_id`, ubah harga/paket | **Diperbaiki** — Form Request + validated only |
| C3 | Password default seeder `password123` | `UserSeeder` · `composer setup --force seed` | Kredensial admin dikenal | **Diperbaiki** — password acak/`SEED_PASSWORD`, seed skip non-local, setup tidak auto-seed |
| C4 | Stored XSS deskripsi produk | `{!! $product->deskripsi !!}` · validasi string | Script vendor dijalankan di browser | **Diperbaiki** — `HtmlSanitizer` + Form Request |

### High

| # | Temuan | Evidence | Dampak | Status |
|---|--------|----------|--------|--------|
| H1 | Payment IDOR (status/refresh/success) | Closure payment di `web.php` | User lain baca/refresh Midtrans by `order_code` | **Diperbaiki** — ownership via `PaymentStatusSync` |
| H2 | PDF laba/rugi untuk semua user login | `LabaRugiReportController` | Customer unduh laporan keuangan | **Diperbaiki** — role `super_admin`/`admin`/`swe` |
| H3 | Upload KTP/PII di disk public | Doorprize `FileUpload` | KTP world-readable via `/storage/...` | **Diperbaiki** — disk `local` private |
| H4 | Login tanpa throttle | `POST /login` | Brute-force | **Diperbaiki** — `throttle:5,1` (+ register) |

### Medium

| # | Temuan | Evidence | Dampak | Status |
|---|--------|----------|--------|--------|
| M1 | Webhook Midtrans kena CSRF | `POST /webhooks/midtrans` di middleware `web` | Notifikasi Midtrans 419 | **Diperbaiki** — CSRF except + `dispatchAfterResponse` |
| M2 | `trustProxies(at: '*')` | `bootstrap/app.php` | Spoof `X-Forwarded-*` | **Diperbaiki** — `TRUSTED_PROXIES` env |
| M3 | RichEditor admin `{!! !!}` | home/blog | XSS jika admin compromised | **Belum** — sanitasi admin content (prioritas lebih rendah) |
| M4 | Tidak ada Form Request | Controllers ad-hoc | Validasi inkonsisten | **Diperbaiki** — vendor/product Form Requests |
| M5 | API vendor expose model penuh | `/api/vendors` | PII scraping | **Diperbaiki** — `VendorResource` allowlist + throttle |
| M6 | Snap error leak Midtrans payload | `PaymentController::createSnap` | Detail internal ke client | **Diperbaiki** — error generik + log server |
| M7 | `SESSION_ENCRYPT=false` | `.env.example` | Session readable jika storage bocor | **Diperbaiki** di contoh env (`true`) |

### Low / positif

- `.env` tidak di-commit; Midtrans keys kosong di example
- `public/.htaccess` Laravel standar (`-Indexes`)
- Shield + `canAccessPanel` untuk Filament
- Signature Midtrans dengan `hash_equals`
- Product create/update & receipt sudah punya ownership (sebelum audit)
- HTTPS force di production

---

## 3. Temuan performa & arsitektur

### High

| # | Temuan | Evidence | Status |
|---|--------|----------|--------|
| P1 | Laba Rugi: aggregate query berulang per kolom/expo | `LabaRugiReport`, `LabaRugiStatsOverview` | **Diperbaiki** — `LabaRugiAggregator` |
| P2 | DoorprizeOverview load semua JSON 2× | `DoorprizeOverview` | **Diperbaiki** — satu pass `flatMap` |
| P3 | Listing publik tanpa pagination | gallery, partners, API | **Sebagian** — gallery paginate; partners tetap full (filter client-side) |
| P4 | Tidak ada Jobs untuk kerja berat | PDF/Midtrans sync di request | **Sebagian** — webhook `dispatchAfterResponse`; PDF masih sync |

### Medium

| # | Temuan | Status |
|---|--------|--------|
| Index hilang (`payments.external_id`, filter partisipasi/expo) | **Diperbaiki** — migration `2026_07_21_000001_...` |
| Zero `Cache::remember` | **Diperbaiki** — navbar, expo, jenis usaha, brand Filament |
| Widget keuangan di setiap halaman admin | **Diperbaiki** — scoped ke Dashboard / Laba Rugi page |
| Fat routes payment di `web.php` | **Diperbaiki** — pindah ke `PaymentController` |
| Cache/session/queue = database | **Dokumentasi** — Redis di `.env.example` (opsional production) |

### Smell maintainability (saat analisa)

- `VendorController` / `PaymentController` gemuk; logika checkout di Blade
- Duplikasi “nearest active expo” → diatasi sebagian via `ExpoResolver`
- Tests hampir skeleton → sekarang ada suite hardening

---

## 4. Yang sudah diperbaiki (changelog teknis)

### P0 — Keamanan kritis (21 Jul 2026 sore)

1. `VendorPolicy` + authorize CRUD; update hanya field tervalidasi  
2. `HtmlSanitizer` + sanitasi deskripsi produk  
3. Ownership payment (success/status/refresh/receipt)  
4. PDF laba/rugi dibatasi role keuangan  
5. Throttle login/register `5,1`  
6. Seeder password aman; `composer setup` tanpa `db:seed --force`  
7. CSRF except Midtrans; `ProcessMidtransWebhook`  
8. Doorprize upload → disk `local` private  
9. `AuthorizesRequests` di base `Controller`

### P1 — Performa & hardening

1. `LabaRugiAggregator` + widget tidak global  
2. Index DB payments / partisipasi / expos / blogs / products  
3. Cache navbar/expo/jenis usaha  
4. Gallery pagination  
5. `TRUSTED_PROXIES`, `SESSION_ENCRYPT=true`, `APP_DEBUG=false` di `.env.example`  
6. Snap error tidak leak payload

### P2 — Arsitektur & tests

1. Form Requests: `Store/UpdateVendorRequest`, `Store/UpdateProductVendorRequest`  
2. `VendorResource` untuk API  
3. `PaymentStatusSync` + route payment di controller  
4. Widget Filament: `isDiscovered=false`, Dashboard `getWidgets()` eksplisit  
5. Feature/unit tests — **11 passed**:
   - `tests/Unit/HtmlSanitizerTest`
   - `tests/Unit/MidtransSignatureTest`
   - `tests/Feature/SecurityHardeningTest`

**File baru penting:**

| Path | Fungsi |
|------|--------|
| `app/Support/HtmlSanitizer.php` | Bersihkan HTML berbahaya |
| `app/Services/LabaRugiAggregator.php` | Aggregate keuangan efisien |
| `app/Services/ExpoResolver.php` | Expo aktif + cache |
| `app/Services/PaymentStatusSync.php` | Sync status Midtrans terpusat |
| `app/Jobs/ProcessMidtransWebhook.php` | Proses webhook setelah response |
| `app/Http/Requests/*` | Validasi vendor/produk |
| `app/Http/Resources/VendorResource.php` | API allowlist |
| `database/migrations/2026_07_21_000001_add_indexes_for_payments_and_filters.php` | Index hot path |

Jalankan test:

```bash
cd /Applications/MAMP/htdocs/sumselweddingexpo
php artisan test --filter='HtmlSanitizerTest|MidtransSignatureTest|SecurityHardeningTest'
```

---

## 5. Yang masih harus dilakukan

### Wajib (ops / data) — lakukan manual

| # | Tindakan | Kenapa | Cara |
|---|----------|--------|------|
| 1 | **Rotasi password** akun seed (`password123` → password baru via `SEED_PASSWORD`) | Seeder lama tidak menimpa user | **Selesai 21 Jul 2026** — akun seed di-update lewat `UserSeeder` + `.env` |
| 2 | Production: `APP_DEBUG=false`, `APP_ENV=production` | Cegah stack trace bocor | Edit `.env` server, lalu `config:clear` / `optimize` |
| 3 | Production: `TRUSTED_PROXIES` = IP/CIDR proxy (bukan `*` jika memungkinkan) | Cegah spoof forwarded headers | Set di `.env` Hostinger/proxy |
| 4 | Production: `SESSION_ENCRYPT=true`, `SESSION_SECURE_COOKIE=true` | Harden session | `.env` + HTTPS |
| 5 | Deploy migration index | Performa webhook & laporan | `php artisan migrate` di server |
| 6 | Pindahkan file KTP/bukti lama dari `storage/app/public/doorprizes/` ke `storage/app/doorprizes/` | Upload baru private; file lama mungkin masih publik | `mv` + pastikan admin Filament masih bisa baca |
| 7 | Pastikan docroot = `public/` | Cegah akses `.env` / source | Konfigurasi hosting |

### Disarankan (production scale)

| # | Tindakan | Catatan |
|---|----------|---------|
| 8 | Redis untuk cache + session (+ queue jika worker ada) | Contoh di `.env.example`; butuh extension/redis di server |
| 9 | Queue worker / cron untuk job berat ke depan | Saat ini webhook sudah `afterResponse` (tanpa worker) |
| 10 | Sanitasi konten RichEditor (home/blog) | Sama pola `HtmlSanitizer` atau package purifier |
| 11 | CSP headers | Kurangi dampak XSS residual |
| 12 | Cache/paginate partners dengan search server-side | Saat ini filter client-side butuh full list |
| 13 | DomPDF laporan → queue atau cache file | Masih sync di request |
| 14 | Pastikan SMTP production untuk verifikasi & reset password | Lokal `MAIL_MAILER=log` — link ada di `storage/logs` |

### Auth frontend (update 21 Jul 2026 malam)

| Fitur | Status |
|-------|--------|
| Login / register + throttle | Ada |
| Lupa / reset password | **Ditambahkan** — `/forgot-password`, `/reset-password/{token}` |
| Halaman verifikasi email | **Ditambahkan** — `/email/verify` |
| Password kuat (huruf besar+kecil+angka, min 8) | **Ditambahkan** (register, reset, ganti password profile) |
| Ganti email → verifikasi ulang | **Ditambahkan** |
| Admin login | Tetap terpisah di `/admin/login` (Filament) |

### Deploy checklist (dari `.info` + audit)

```bash
cd /home/u380354370/domains/sumselweddingexpo.com/public_html   # sesuaikan path
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
php artisan filament:optimize
# Pastikan: APP_DEBUG=false, password admin sudah diganti
```

---

## 6. Roadmap singkat

| Fase | Fokus | Status |
|------|--------|--------|
| **P0** | IDOR vendor, mass assignment, XSS, payment ownership, PDF role, throttle, seeder, KTP private, CSRF webhook | ✅ Selesai 21 Jul 2026 |
| **P1** | Laba Rugi query, index DB, cache, gallery paginate, session/env hardening | ✅ Selesai 21 Jul 2026 |
| **P2** | Form Request, API Resource, PaymentStatusSync, widget scope, tests | ✅ Selesai 21 Jul 2026 |
| **Ops** | Redis prod, migrasi file KTP, deploy migrate | ⏳ Manual (password seed sudah dirotasi lokal) |
| **P3+** | CSP, sanitasi RichEditor, PDF async, partners server-search, lebih banyak tests | ⏳ Backlog |

---

## 7. Kontak konteks domain

| Peran (Spatie) | Akses tipikal |
|----------------|---------------|
| `super_admin` / `admin` | Filament panel + laporan keuangan |
| `swe` | Penyelenggara; PDF laba/rugi diizinkan setelah fix |
| `customer` | Portal user; tidak boleh kelola vendor orang lain / unduh laporan keuangan |

---

*Dokumen ini dibuat dari audit 21 Juli 2026 dan diperbarui setelah implementasi perbaikan P0–P2 di sesi yang sama. Update lagi jika ada temuan baru atau rilis hardening berikutnya.*
