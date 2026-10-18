<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expo_id')->constrained('expos')->cascadeOnDelete();
            $table->string('email');
            $table->string('no_tlp', 20);
            $table->date('tanggal');
            $table->timestamps();

            $table->unique(['expo_id', 'email', 'tanggal']);
            $table->unique(['expo_id', 'no_tlp', 'tanggal']);
        });

        if (! Schema::hasTable('permissions') || ! Schema::hasTable('roles')) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = config('auth.defaults.guard', 'web');
        $permissions = collect([
            'ViewAny:Absensi',
            'View:Absensi',
            'Create:Absensi',
            'Update:Absensi',
            'Delete:Absensi',
            'DeleteAny:Absensi',
            'Restore:Absensi',
            'RestoreAny:Absensi',
            'ForceDelete:Absensi',
            'ForceDeleteAny:Absensi',
            'Replicate:Absensi',
            'Reorder:Absensi',
        ])->map(fn (string $name) => Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => $guard,
        ]));

        foreach (['super_admin', 'admin'] as $roleName) {
            Role::query()
                ->where('name', $roleName)
                ->where('guard_name', $guard)
                ->first()
                ?->givePermissionTo($permissions);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');

        if (! Schema::hasTable('permissions')) {
            return;
        }

        Permission::query()
            ->where('name', 'like', '%:Absensi')
            ->delete();
    }
};
