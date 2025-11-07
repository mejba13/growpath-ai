<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts.
     */
    public function index()
    {
        $posts = BlogPost::with(['category', 'author', 'tags'])
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('frontend.blog', compact('posts'));
    }

    /**
     * Display a single blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Get related posts from same category
        $relatedPosts = BlogPost::with(['category', 'author'])
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('frontend.blog-detail', compact('post', 'relatedPosts'));
    }
}
