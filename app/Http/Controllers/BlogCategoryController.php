<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->latest()->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:blog_categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        $category = BlogCategory::create($validated);

        return response()->json([
            'success' => true,
            'category' => $category,
            'message' => 'Category created successfully',
        ]);
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:blog_categories,name,'.$blogCategory->id],
            'description' => ['nullable', 'string'],
        ]);

        $blogCategory->update($validated);

        return response()->json([
            'success' => true,
            'category' => $blogCategory,
            'message' => 'Category updated successfully',
        ]);
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
