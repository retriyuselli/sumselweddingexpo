<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'content',
        'blog_category_id',
        'category_color',
        'user_id',
        'date',
        'read_time',
        'image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'date' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Alias for backward compatibility
    public function author(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Get article by slug
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->where('is_published', true)->firstOrFail();
    }

    /**
     * Get formatted article data for view
     */
    public function toArticleArray()
    {
        return [
            'title' => $this->title,
            'breadcrumb' => $this->title,
            'excerpt' => $this->excerpt,
            'category' => $this->category->name ?? null,
            'category_color' => $this->category_color,
            'author' => [
                'name' => $this->user->name ?? null,
                'role' => $this->user->getRoleNameAttribute() ?? null,
                'bio' => $this->user->bio ?? null,
                'color' => $this->user->author_color ?? '3b82f6',
            ],
            'date' => $this->date->format('d F Y'),
            'read_time' => $this->read_time,
            'image' => $this->image,
            'content' => $this->content,
        ];
    }

    /**
     * Get all published articles
     */
    public static function published()
    {
        return self::where('is_published', true)->orderBy('date', 'desc')->get();
    }

    /**
     * Get articles by category
     */
    public static function byCategory($categoryId)
    {
        return self::where('blog_category_id', $categoryId)
            ->where('is_published', true)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get popular articles (for sidebar)
     */
    public static function popular($limit = 4)
    {
        return self::where('is_published', true)
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get();
    }
}
