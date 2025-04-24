<?php

namespace App\Http\Controllers;

use App\Models\SeoSettings;
use App\Models\Vitrin;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeoSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seoSettings = SeoSettings::with('vitrin')->get();
        return Inertia::render('SeoSettings/Index', [
            'seoSettings' => $seoSettings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vitrins = Vitrin::all();
        return Inertia::render('SeoSettings/Create', [
            'vitrins' => $vitrins
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vitrin_id' => 'required|exists:vitrins,id',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string',
            'twitter_title' => 'nullable|string|max:60',
            'twitter_description' => 'nullable|string|max:160',
            'twitter_image' => 'nullable|string',
            'schema_markup' => 'nullable|array',
            'index_follow' => 'boolean',
        ]);

        SeoSettings::create($validated);

        return redirect()->route('seo-settings.index')
            ->with('success', 'SEO settings created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeoSettings $seoSettings)
    {
        $vitrins = Vitrin::all();
        return Inertia::render('SeoSettings/Edit', [
            'seoSettings' => $seoSettings,
            'vitrins' => $vitrins
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SeoSettings $seoSettings)
    {
        $validated = $request->validate([
            'vitrin_id' => 'required|exists:vitrins,id',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|string',
            'twitter_title' => 'nullable|string|max:60',
            'twitter_description' => 'nullable|string|max:160',
            'twitter_image' => 'nullable|string',
            'schema_markup' => 'nullable|array',
            'index_follow' => 'boolean',
        ]);

        $seoSettings->update($validated);

        return redirect()->route('seo-settings.index')
            ->with('success', 'SEO settings updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeoSettings $seoSettings)
    {
        $seoSettings->delete();

        return redirect()->route('seo-settings.index')
            ->with('success', 'SEO settings deleted successfully.');
    }
}
