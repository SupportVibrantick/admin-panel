<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('categories')->latest()->get();
        return view('admin.pages.blogs', compact('blogs'));
    }
    // for slug
    public function details($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return view('website.pages.blog-details', compact('blog'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:blog_categories,id',
            'description' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp',
            'published_at' => 'required|date',
            'status' => 'required|in:active,inactive',
            'benefits' => 'required',
            'quote' => 'required|string|max:255'

        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $data['category_ids'] = $request->category_ids;

        Blog::create($data);

        return back()->with('success', 'Blog created successfully!');
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:blog_categories,id',
            'description' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
            'published_at' => 'required|date',
            'status' => 'required|in:active,inactive',
            'benefits' => 'required',
            'quote' => 'required|string|max:255'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($blog->image)
                Storage::disk('public')->delete($blog->image);
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $data['category_ids'] = $request->category_ids;

        $blog->update($data);

        return back()->with('success', 'Blog updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }
        $blog->delete();
        return back()->with('success', 'Blog deleted successfully!');
    }
}