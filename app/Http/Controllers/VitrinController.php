<?php

namespace App\Http\Controllers;

use App\Models\Vitrin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VitrinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clinic');
    }

    public function index()
    {
        $vitrin = auth()->user()->vitrin;
        return view('vitrin.manage.index', compact('vitrin'));
    }

    public function edit()
    {
        $vitrin = auth()->user()->vitrin;
        return view('vitrin.manage.edit', compact('vitrin'));
    }

    public function update(Request $request)
    {
        $vitrin = auth()->user()->vitrin;
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mission' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'business_hours' => ['required', 'string'],
            'theme' => ['required', 'string', Rule::in(['default', 'modern', 'minimal'])],
            'seo_title' => ['nullable', 'string', 'max:60'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'facebook' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'max:1024'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($vitrin->logo) {
                Storage::delete($vitrin->logo);
            }
            $validated['logo'] = $request->file('logo')->store('vitrin/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($vitrin->cover_image) {
                Storage::delete($vitrin->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('vitrin/covers', 'public');
        }

        $vitrin->update($validated);

        return redirect()->route('vitrin.manage.index')
            ->with('success', 'Vitrin settings updated successfully.');
    }

    public function preview()
    {
        $vitrin = auth()->user()->vitrin;
        return view('vitrin.manage.preview', compact('vitrin'));
    }

    public function publish()
    {
        $vitrin = auth()->user()->vitrin;
        $vitrin->update(['is_published' => true]);

        return redirect()->route('vitrin.manage.index')
            ->with('success', 'Vitrin published successfully.');
    }

    public function unpublish()
    {
        $vitrin = auth()->user()->vitrin;
        $vitrin->update(['is_published' => false]);

        return redirect()->route('vitrin.manage.index')
            ->with('success', 'Vitrin unpublished successfully.');
    }

    public function statistics()
    {
        $vitrin = auth()->user()->vitrin;
        $stats = [
            'total_visitors' => $vitrin->visitors()->count(),
            'unique_visitors' => $vitrin->visitors()->distinct('ip_address')->count(),
            'page_views' => $vitrin->page_views()->count(),
            'average_time' => $vitrin->visitors()->avg('time_spent'),
            'bounce_rate' => $vitrin->calculateBounceRate(),
            'top_pages' => $vitrin->getTopPages(),
            'traffic_sources' => $vitrin->getTrafficSources(),
            'search_rankings' => $vitrin->getSearchRankings(),
        ];

        return view('vitrin.manage.statistics', compact('vitrin', 'stats'));
    }

    public function media()
    {
        $vitrin = auth()->user()->vitrin;
        $media = $vitrin->media()->latest()->paginate(20);
        
        return view('vitrin.manage.media', compact('vitrin', 'media'));
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'media' => ['required', 'array'],
            'media.*' => ['required', 'file', 'max:10240'],
        ]);

        $vitrin = auth()->user()->vitrin;
        $uploadedFiles = [];

        foreach ($request->file('media') as $file) {
            $path = $file->store('vitrin/media', 'public');
            $uploadedFiles[] = $vitrin->media()->create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return response()->json([
            'message' => 'Media uploaded successfully',
            'files' => $uploadedFiles,
        ]);
    }

    public function deleteMedia($id)
    {
        $vitrin = auth()->user()->vitrin;
        $media = $vitrin->media()->findOrFail($id);
        
        Storage::delete($media->path);
        $media->delete();

        return redirect()->route('vitrin.manage.media')
            ->with('success', 'Media deleted successfully.');
    }
} 