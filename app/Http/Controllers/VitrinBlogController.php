<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Vitrin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VitrinBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clinic');
    }

    public function index()
    {
        $vitrin = auth()->user()->vitrin;
        $posts = $vitrin->blogs()->latest()->paginate(10);
        
        return view('vitrin.manage.blog.index', compact('vitrin', 'posts'));
    }

    public function create()
    {
        $vitrin = auth()->user()->vitrin;
        return view('vitrin.manage.blog.create', compact('vitrin'));
    }

    public function store(Request $request)
    {
        $vitrin = auth()->user()->vitrin;
        
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('vitrin/blog/featured', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();
        $validated['vitrin_id'] = $vitrin->id;

        $post = Blog::create($validated);

        if ($request->has('tags')) {
            $post->syncTags($request->tags);
        }

        return redirect()->route('vitrin.manage.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);
        $vitrin = auth()->user()->vitrin;
        
        return view('vitrin.manage.blog.edit', compact('vitrin', 'blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->authorize('update', $blog);
        
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
        ]);

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('vitrin/blog/featured', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        $blog->update($validated);

        if ($request->has('tags')) {
            $blog->syncTags($request->tags);
        }

        return redirect()->route('vitrin.manage.blog.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);
        
        if ($blog->featured_image) {
            Storage::delete($blog->featured_image);
        }
        
        $blog->delete();

        return redirect()->route('vitrin.manage.blog.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('image')->store('vitrin/blog/content', 'public');

        return response()->json([
            'url' => Storage::url($path),
        ]);
    }

    public function publish(Blog $blog)
    {
        $this->authorize('update', $blog);
        
        $blog->update(['is_published' => true]);

        return redirect()->route('vitrin.manage.blog.index')
            ->with('success', 'Blog post published successfully.');
    }

    public function unpublish(Blog $blog)
    {
        $this->authorize('update', $blog);
        
        $blog->update(['is_published' => false]);

        return redirect()->route('vitrin.manage.blog.index')
            ->with('success', 'Blog post unpublished successfully.');
    }
} 