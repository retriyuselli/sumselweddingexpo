<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index()
    {
        $blogs = Blog::with(['category', 'user'])
            ->where('is_published', true)
            ->orderBy('date', 'desc')
            ->paginate(9);

        // Process images for paginated blogs
        foreach ($blogs as $blog) {
            $this->processBlogImage($blog);
        }

        $categories = BlogCategory::withCount('blogs')->get();

        $featuredBlog = Blog::with(['category', 'user'])
            ->where('is_published', true)
            ->orderBy('date', 'desc')
            ->first();

        if ($featuredBlog) {
            $this->processBlogImage($featuredBlog);
        }

        return view('blog.index', compact('blogs', 'categories', 'featuredBlog'));
    }

    /**
     * Display the specified blog post
     */
    public function show($slug)
    {
        $blog = Blog::with(['category', 'user'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $this->processBlogImage($blog);

        // Get related blogs from same category
        $relatedBlogs = Blog::with(['category', 'user'])
            ->where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->where('is_published', true)
            ->orderBy('date', 'desc')
            ->limit(3)
            ->get();

        foreach ($relatedBlogs as $related) {
            $this->processBlogImage($related);
        }

        // Get recent blogs
        $recentBlogs = Blog::with(['category', 'user'])
            ->where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->orderBy('date', 'desc')
            ->limit(4)
            ->get();

        foreach ($recentBlogs as $recent) {
            $this->processBlogImage($recent);
        }

        // Format article data for view
        $article = $blog->toArticleArray();
        // Ensure article array has the correct image URL
        $article['image'] = $blog->image_url;

        return view('blog.show', compact('article', 'relatedBlogs', 'recentBlogs', 'blog'));
    }

    /**
     * Display blogs by category
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $blogs = Blog::with(['category', 'user'])
            ->where('blog_category_id', $category->id)
            ->where('is_published', true)
            ->orderBy('date', 'desc')
            ->paginate(9);

        foreach ($blogs as $blog) {
            $this->processBlogImage($blog);
        }

        $categories = BlogCategory::withCount('blogs')->get();

        return view('blog.category', compact('blogs', 'category', 'categories'));
    }

    /**
     * Search blogs
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $blogs = Blog::with(['category', 'user'])
            ->where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(9);

        foreach ($blogs as $blog) {
            $this->processBlogImage($blog);
        }

        $categories = BlogCategory::withCount('blogs')->get();

        return view('blog.index', compact('blogs', 'categories'));
    }

    /**
     * Process blog image URL
     */
    private function processBlogImage($blog)
    {
        $defaultImage = 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=600&fit=crop&auto=format&q=80';

        if (empty($blog->image)) {
            $blog->setAttribute('image_url', $defaultImage);
            return;
        }

        if (filter_var($blog->image, FILTER_VALIDATE_URL)) {
            $blog->setAttribute('image_url', $blog->image);
            return;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($blog->image)) {
            $blog->setAttribute('image_url', asset('storage/' . $blog->image));
            return;
        }

        $blog->setAttribute('image_url', $defaultImage);
    }
}
