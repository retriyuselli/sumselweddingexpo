<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'bio',
        'tentang',
        'media_sosial',
        'jabatan',
        'author_color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'media_sosial' => 'array',
        ];
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin']);
    }

    /**
     * Get the URL of the user's avatar for Filament.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        if (! $this->avatar_url || ! Storage::disk('public')->exists($this->avatar_url)) {
            return null;
        }

        // Return public URL with cache busting
        return Storage::url($this->avatar_url).'?v='.Storage::disk('public')->lastModified($this->avatar_url);
    }

    /**
     * Get the avatar URL with cache busting.
     */
    public function getAvatarAttribute(): ?string
    {
        if (! $this->avatar_url || ! Storage::disk('public')->exists($this->avatar_url)) {
            return null;
        }

        // Return public URL with cache busting
        return Storage::url($this->avatar_url).'?v='.Storage::disk('public')->lastModified($this->avatar_url);
    }

    /**
     * Check if user has an avatar.
     */
    public function hasAvatar(): bool
    {
        return $this->avatar_url && Storage::disk('public')->exists($this->avatar_url);
    }

    /**
     * Get user's initials for avatar fallback.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Check if user's email is verified.
     */
    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Get user's role display name.
     */
    public function getRoleNameAttribute(): string
    {
        if ($this->roles->isNotEmpty()) {
            return $this->roles->pluck('name')->join(', ');
        }

        return 'User';
    }

    /**
     * Relationship: User has many Pengeluaran (Expenses)
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    /**
     * Relationship: User has many PengeluaranLain (Other Expenses)
     */
    public function pengeluaranLains()
    {
        return $this->hasMany(PengeluaranLain::class);
    }

    /**
     * Relationship: User has many Blogs (as author)
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id');
    }

    /**
     * Scope: Get only verified users.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope: Get only unverified users.
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Scope: Get users with specific role.
     */
    public function scopeWithRole($query, string $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }
}
