<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expo_id',
        'title',
        'description',
        'image_path',
        'photographer_name',
        'photo_date',
        'display_order',
        'is_featured',
        'is_published',
        'tags',
    ];

    protected $casts = [
        'photo_date' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'tags' => 'array',
        'image_path' => 'array',
    ];

    protected $appends = [
        'image_urls',
        'primary_image_url',
    ];

    // Relationships
    public function expo()
    {
        return $this->belongsTo(Expo::class);
    }

    // Accessors
    public function getImageUrlsAttribute(): array
    {
        if (empty($this->image_path) || ! is_array($this->image_path)) {
            return [asset('images/placeholder-gallery.jpg')];
        }

        return array_map(function ($path) {
            // Jika sudah berupa URL absolut
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            // Cek apakah file ada
            if (Storage::disk('public')->exists($path)) {
                return asset('storage/'.$path);
            }

            return asset('images/placeholder-gallery.jpg');
        }, $this->image_path);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $urls = $this->image_urls;

        return $urls[0] ?? asset('images/placeholder-gallery.jpg');
    }

    // Legacy accessor for backward compatibility
    public function getImageUrlAttribute(): string
    {
        return $this->primary_image_url;
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForExpo($query, $expoId)
    {
        return $query->where('expo_id', $expoId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('created_at', 'desc');
    }
}
