<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('posts')->latest()->get();

        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:blog_tags,name'],
        ]);

        $tag = BlogTag::create($validated);

        return response()->json([
            'success' => true,
            'tag' => $tag,
            'message' => 'Tag created successfully',
        ]);
    }

    public function update(Request $request, BlogTag $blogTag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:blog_tags,name,'.$blogTag->id],
        ]);

        $blogTag->update($validated);

        return response()->json([
            'success' => true,
            'tag' => $blogTag,
            'message' => 'Tag updated successfully',
        ]);
    }

    public function destroy(BlogTag $blogTag)
    {
        $blogTag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully',
        ]);
    }
}
