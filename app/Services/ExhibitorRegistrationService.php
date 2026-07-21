<?php

namespace App\Services;

use App\Models\CategoryTenant;
use App\Models\Partisipasi;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExhibitorRegistrationService
{
    public function __construct(
        private ExpoResolver $expoResolver,
    ) {}

    /**
     * Create vendor identity + draft partisipasi for the nearest active expo.
     *
     * @param  array<string, mixed>  $vendorData
     * @param  array<string, mixed>  $participationData
     */
    public function register(array $vendorData, array $participationData, ?int $userId = null): Vendor
    {
        return DB::transaction(function () use ($vendorData, $participationData, $userId) {
            if ($userId) {
                $vendorData['user_id'] = $userId;
            }

            $vendor = Vendor::create($vendorData);
            $this->createPartisipasi($vendor, $participationData);

            return $vendor;
        });
    }

    /**
     * Attach an existing vendor to the nearest active expo.
     *
     * @param  array<string, mixed>  $participationData
     */
    public function joinExpo(Vendor $vendor, array $participationData): Partisipasi
    {
        return DB::transaction(function () use ($vendor, $participationData) {
            return $this->createPartisipasi($vendor, $participationData);
        });
    }

    /**
     * @param  array<string, mixed>  $participationData
     *
     * @throws ValidationException
     */
    public function createPartisipasi(Vendor $vendor, array $participationData): Partisipasi
    {
        $expo = $this->expoResolver->nearestActive();
        if (! $expo) {
            throw ValidationException::withMessages([
                'paket' => 'Tidak ada expo aktif untuk pendaftaran saat ini.',
            ]);
        }

        $already = Partisipasi::where('vendor_id', $vendor->id)
            ->where('expo_id', $expo->id)
            ->exists();
        if ($already) {
            throw ValidationException::withMessages([
                'paket' => 'Vendor Anda sudah terdaftar pada expo ini.',
            ]);
        }

        $paket = (string) ($participationData['paket'] ?? '');
        $categoryTenant = CategoryTenant::query()
            ->where('expo_id', $expo->id)
            ->where('category', $paket)
            ->where('status', 'Aktif')
            ->first();

        if (! $categoryTenant) {
            throw ValidationException::withMessages([
                'paket' => 'Paket tidak tersedia untuk expo aktif.',
            ]);
        }

        // Always use catalog price — never trust client-submitted harga_jual
        $hargaJual = (int) ($categoryTenant->harga_jual ?? 0);
        $lokasi = trim((string) ($participationData['lokasi_booth'] ?? ''));
        $pendamping = trim((string) ($participationData['pendamping_tenant'] ?? ''));

        $keteranganParts = [];
        if ($pendamping !== '') {
            $keteranganParts[] = 'Pendamping: '.$pendamping;
        }
        if ($lokasi !== '') {
            $keteranganParts[] = 'Preferensi booth: '.$lokasi;
        }

        return Partisipasi::create([
            'expo_id' => $expo->id,
            'vendor_id' => $vendor->id,
            'tanggal_booking' => now()->toDateString(),
            'status_pembayaran' => 'Belum Lunas',
            'category_tenant_id' => $categoryTenant->id,
            'blok_tenant' => $lokasi !== '' ? $lokasi : null,
            'harga_jual' => $hargaJual,
            'diskon' => 0,
            'keterangan' => $keteranganParts !== [] ? implode(' | ', $keteranganParts) : null,
            'vendor_pendamping' => null,
        ]);
    }
}
