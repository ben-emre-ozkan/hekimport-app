<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vitrin;
use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

class VitrinViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('vitrin');
    }

    public function home(Request $request)
    {
        $vitrin = $request->attributes->get('vitrin');
        
        return view('vitrin.themes.default.home', [
            'vitrin' => $vitrin,
            'recentBlogs' => Cache::remember("vitrin.{$vitrin->id}.recent_blogs", 3600, function () use ($vitrin) {
                return Blog::where('vitrin_id', $vitrin->id)
                    ->latest()
                    ->take(3)
                    ->get();
            })
        ]);
    }

    public function about(Request $request)
    {
        $vitrin = $request->attributes->get('vitrin');
        return view('vitrin.themes.default.about', compact('vitrin'));
    }

    public function contact(Request $request)
    {
        $vitrin = $request->attributes->get('vitrin');
        return view('vitrin.themes.default.contact', compact('vitrin'));
    }

    public function blog(Request $request)
    {
        $vitrin = $request->attributes->get('vitrin');
        $blogs = Blog::where('vitrin_id', $vitrin->id)
            ->latest()
            ->paginate(10);

        return view('vitrin.themes.default.blog.index', compact('vitrin', 'blogs'));
    }

    public function blogShow(Request $request, $slug)
    {
        $vitrin = $request->attributes->get('vitrin');
        $blog = Blog::where('vitrin_id', $vitrin->id)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('vitrin.themes.default.blog.show', compact('vitrin', 'blog'));
    }

    public function preview(Request $request, $vitrinId)
    {
        $this->middleware('auth');
        
        $vitrin = Vitrin::findOrFail($vitrinId);
        view()->share('vitrin', $vitrin);
        
        return view('vitrin.themes.default.preview', compact('vitrin'));
    }
} 