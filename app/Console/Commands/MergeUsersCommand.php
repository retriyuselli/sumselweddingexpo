<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\Blog;
use App\Models\Order;
use App\Models\Pengeluaran;
use App\Models\PengeluaranLain;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class MergeUsersCommand extends Command
{
    protected $signature = 'user:merge
                            {keep : Email atau ID user yang dipertahankan}
                            {merge : Email atau ID user yang digabung lalu dihapus}
                            {--dry-run : Tampilkan rencana tanpa mengubah database}';

    protected $description = 'Gabungkan dua user menjadi satu (roles, google_id, dan data terkait)';

    public function handle(): int
    {
        $keep = $this->resolveUser((string) $this->argument('keep'));
        $merge = $this->resolveUser((string) $this->argument('merge'));

        if (! $keep || ! $merge) {
            $this->error('User keep dan/atau merge tidak ditemukan.');

            return self::FAILURE;
        }

        if ($keep->id === $merge->id) {
            $this->error('Keep dan merge merujuk ke user yang sama.');

            return self::FAILURE;
        }

        $this->table(
            ['', 'ID', 'Email', 'Google ID', 'Roles'],
            [
                ['KEEP', $keep->id, $keep->email, $keep->google_id ?: '-', $keep->getRoleNames()->join(', ') ?: '-'],
                ['MERGE', $merge->id, $merge->email, $merge->google_id ?: '-', $merge->getRoleNames()->join(', ') ?: '-'],
            ]
        );

        $plan = [
            'vendor' => Vendor::query()->where('user_id', $merge->id)->count(),
            'blogs' => Blog::query()->where('user_id', $merge->id)->count(),
            'appointments' => Appointment::query()->where('customer_id', $merge->id)->count(),
            'orders' => Order::query()->where('customer_id', $merge->id)->count(),
            'pengeluaran' => Pengeluaran::query()->where('user_id', $merge->id)->count(),
            'pengeluaran_lain' => PengeluaranLain::query()->where('user_id', $merge->id)->count(),
        ];

        $this->info('Data yang akan dipindahkan dari MERGE → KEEP:');
        foreach ($plan as $label => $count) {
            $this->line("  - {$label}: {$count}");
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry-run: tidak ada perubahan yang disimpan.');

            return self::SUCCESS;
        }

        if (! $this->confirm("Gabungkan {$merge->email} ke {$keep->email} dan hapus user merge?", true)) {
            $this->warn('Dibatalkan.');

            return self::SUCCESS;
        }

        try {
            DB::transaction(function () use ($keep, $merge) {
                foreach ($merge->getRoleNames() as $role) {
                    if (! $keep->hasRole($role)) {
                        $keep->assignRole($role);
                    }
                }

                $updates = [];

                if (blank($keep->google_id) && filled($merge->google_id)) {
                    $updates['google_id'] = $merge->google_id;
                }

                if (blank($keep->email_verified_at) && filled($merge->email_verified_at)) {
                    $updates['email_verified_at'] = $merge->email_verified_at;
                }

                if (blank($keep->avatar_url) && filled($merge->avatar_url)) {
                    $updates['avatar_url'] = $merge->avatar_url;
                }

                // Lepas google_id dari merge dulu agar unique constraint aman.
                if (filled($merge->google_id)) {
                    $merge->forceFill(['google_id' => null])->save();
                }

                if ($updates !== []) {
                    $keep->forceFill($updates)->save();
                }

                $keepHasVendor = Vendor::query()->where('user_id', $keep->id)->exists();
                if ($keepHasVendor) {
                    Vendor::query()->where('user_id', $merge->id)->update(['user_id' => null]);
                } else {
                    Vendor::query()->where('user_id', $merge->id)->update(['user_id' => $keep->id]);
                }

                Blog::query()->where('user_id', $merge->id)->update(['user_id' => $keep->id]);
                Appointment::query()->where('customer_id', $merge->id)->update(['customer_id' => $keep->id]);
                Order::query()->where('customer_id', $merge->id)->update(['customer_id' => $keep->id]);
                Pengeluaran::query()->where('user_id', $merge->id)->update(['user_id' => $keep->id]);
                PengeluaranLain::query()->where('user_id', $merge->id)->update(['user_id' => $keep->id]);

                $merge->syncRoles([]);
                $merge->delete();
            });
        } catch (Throwable $exception) {
            report($exception);
            $this->error('Gagal menggabungkan user: '.$exception->getMessage());

            return self::FAILURE;
        }

        $keep->refresh()->load('roles');

        $this->info("Berhasil. User aktif: #{$keep->id} {$keep->email}");
        $this->line('Roles: '.$keep->getRoleNames()->join(', '));
        $this->line('Google ID: '.($keep->google_id ?: '-'));
        $this->comment('Login Google berikutnya akan masuk ke akun KEEP (cocok via google_id).');

        return self::SUCCESS;
    }

    private function resolveUser(string $identifier): ?User
    {
        $query = User::query()->with('roles');

        if (ctype_digit($identifier)) {
            return $query->find((int) $identifier);
        }

        return $query->where('email', strtolower(trim($identifier)))->first();
    }
}
