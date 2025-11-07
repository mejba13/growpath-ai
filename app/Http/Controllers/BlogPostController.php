<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['category', 'author', 'tags'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => BlogPost::count(),
            'published' => BlogPost::where('status', 'published')->count(),
            'draft' => BlogPost::where('status', 'draft')->count(),
        ];

        return view('blog.index', compact('posts', 'stats'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();

        return view('blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'status' => ['required', 'in:draft,published'],
            'tags' => ['nullable', 'array'],
        ]);

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $post = BlogPost::create($validated);

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function show(BlogPost $blogPost)
    {
        $blogPost->load(['category', 'author', 'tags']);

        return view('blog.show', compact('blogPost'));
    }

    public function edit(BlogPost $blogPost)
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        $blogPost->load('tags');

        return view('blog.edit', compact('blogPost', 'categories', 'tags'));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'status' => ['required', 'in:draft,published'],
            'tags' => ['nullable', 'array'],
        ]);

        if ($validated['status'] === 'published' && $blogPost->status === 'draft') {
            $validated['published_at'] = now();
        }

        $blogPost->update($validated);

        if (isset($validated['tags'])) {
            $blogPost->tags()->sync($validated['tags']);
        }

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return redirect()->route('blog-posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
