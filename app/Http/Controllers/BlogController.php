<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;

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

        $categories = BlogCategory::withCount('blogs')->get();

        $featuredBlog = Blog::with(['category', 'user'])
            ->where('is_published', true)
            ->orderBy('date', 'desc')
            ->first();

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

        // Get related blogs from same category
        $relatedBlogs = Blog::with(['category', 'user'])
            ->where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->where('is_published', true)
            ->orderBy('date', 'desc')
            ->limit(3)
            ->get();

        // Get recent blogs
        $recentBlogs = Blog::with(['category', 'user'])
            ->where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->orderBy('date', 'desc')
            ->limit(4)
            ->get();

        // Format article data for view
        $article = $blog->toArticleArray();

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
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(9);

        $categories = BlogCategory::withCount('blogs')->get();

        return view('blog.search', compact('blogs', 'query', 'categories'));
    }
}
