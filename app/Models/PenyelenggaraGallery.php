<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PenyelenggaraGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'penyelenggara_id',
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
        'image_path' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'photo_date' => 'date',
    ];

    protected $appends = [
        'image_urls',
        'primary_image_url',
    ];

    public function penyelenggara()
    {
        return $this->belongsTo(Penyelenggara::class);
    }

    // Accessors
    public function getImageUrlsAttribute(): array
    {
        if (empty($this->image_path) || ! is_array($this->image_path)) {
            return [asset('images/placeholder-gallery.jpg')];
        }

        return array_map(function ($path) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

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

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('created_at', 'desc');
    }
}
