<?php

use App\Models\CategoryTenant;
use App\Models\Partisipasi;
use App\Models\Vendor;
use App\Services\ExpoResolver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Backfill: move expo-specific vendor fields into partisipasi when possible
        if (
            Schema::hasColumn('vendors', 'paket')
            || Schema::hasColumn('vendors', 'lokasi_booth')
            || Schema::hasColumn('vendors', 'harga_jual')
            || Schema::hasColumn('vendors', 'pendamping_tenant')
        ) {
            $expo = app(ExpoResolver::class)->nearestActive();

            if ($expo) {
                Vendor::query()
                    ->where(function ($q) {
                        $q->whereNotNull('paket')
                            ->orWhereNotNull('lokasi_booth')
                            ->orWhereNotNull('harga_jual')
                            ->orWhereNotNull('pendamping_tenant');
                    })
                    ->orderBy('id')
                    ->chunkById(100, function ($vendors) use ($expo) {
                        foreach ($vendors as $vendor) {
                            $exists = Partisipasi::where('vendor_id', $vendor->id)
                                ->where('expo_id', $expo->id)
                                ->exists();
                            if ($exists) {
                                continue;
                            }

                            $paket = (string) ($vendor->getAttributes()['paket'] ?? '');
                            if ($paket === '') {
                                continue;
                            }

                            $categoryTenant = CategoryTenant::query()
                                ->where('expo_id', $expo->id)
                                ->where('category', $paket)
                                ->first();

                            if (! $categoryTenant) {
                                continue;
                            }

                            $lokasi = trim((string) ($vendor->getAttributes()['lokasi_booth'] ?? ''));
                            $pendamping = trim((string) ($vendor->getAttributes()['pendamping_tenant'] ?? ''));
                            $harga = (int) ($vendor->getAttributes()['harga_jual'] ?? $categoryTenant->harga_jual ?? 0);

                            $notes = [];
                            if ($pendamping !== '') {
                                $notes[] = 'Pendamping: '.$pendamping;
                            }
                            if ($lokasi !== '') {
                                $notes[] = 'Preferensi booth: '.$lokasi;
                            }

                            Partisipasi::create([
                                'expo_id' => $expo->id,
                                'vendor_id' => $vendor->id,
                                'tanggal_booking' => now()->toDateString(),
                                'status_pembayaran' => 'Belum Lunas',
                                'category_tenant_id' => $categoryTenant->id,
                                'blok_tenant' => $lokasi !== '' ? $lokasi : null,
                                'harga_jual' => $harga,
                                'diskon' => 0,
                                'keterangan' => $notes !== [] ? implode(' | ', $notes) : 'Backfill dari data vendor',
                            ]);
                        }
                    });
            }
        }

        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'harga_jual')) {
                $table->dropColumn('harga_jual');
            }
            if (Schema::hasColumn('vendors', 'lokasi_booth')) {
                $table->dropColumn('lokasi_booth');
            }
            if (Schema::hasColumn('vendors', 'paket')) {
                $table->dropColumn('paket');
            }
            if (Schema::hasColumn('vendors', 'pendamping_tenant')) {
                $table->dropColumn('pendamping_tenant');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'paket')) {
                $table->string('paket')->nullable()->after('no_wa_pic');
            }
            if (! Schema::hasColumn('vendors', 'lokasi_booth')) {
                $table->string('lokasi_booth', 100)->nullable()->after('paket');
            }
            if (! Schema::hasColumn('vendors', 'harga_jual')) {
                $table->unsignedBigInteger('harga_jual')->nullable()->after('lokasi_booth');
            }
            if (! Schema::hasColumn('vendors', 'pendamping_tenant')) {
                $table->string('pendamping_tenant')->nullable()->after('no_telepon');
            }
        });
    }
};
