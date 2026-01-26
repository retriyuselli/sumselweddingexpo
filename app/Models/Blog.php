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
            'id' => $this->id,
            'title' => $this->title,
            'breadcrumb' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category' => $this->category ? $this->category->name : 'Uncategorized',
            'category_color' => $this->category_color,
            'read_time' => $this->read_time,
            'image' => $this->image, // Return raw image, let controller handle URL
            'date' => $this->date ? $this->date->isoFormat('D MMMM Y') : $this->created_at->isoFormat('D MMMM Y'),
            'datetime' => $this->date ? $this->date->format('Y-m-d') : $this->created_at->format('Y-m-d'),
            'author' => [
                'name' => $this->user ? $this->user->name : 'Admin',
                'avatar' => ($this->user && $this->user->avatar) ? $this->user->avatar : 'https://ui-avatars.com/api/?name=' . ($this->user ? urlencode($this->user->name) : 'Admin'),
                'role' => ($this->user && $this->user->roles->first()) ? $this->user->roles->first()->name : 'Admin',
                'bio' => $this->user ? $this->user->bio : null,
                'color' => $this->user->author_color ?? '3b82f6',
            ],
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
