<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentVitrin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentVitrinController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except(['show']);
    }

    /**
     * Display the specified vitrin.
     */
    public function show(string $subdomain)
    {
        $student = Student::where('subdomain', $subdomain)
            ->verified()
            ->firstOrFail();

        if (!$student->isPublic() && (!auth()->check() || auth()->id() !== $student->user_id)) {
            abort(404);
        }

        return view('akademi.vitrin.show', [
            'student' => $student,
            'vitrin' => $student->vitrin,
        ]);
    }

    /**
     * Show the form for editing the vitrin.
     */
    public function edit()
    {
        $student = auth()->user()->student;
        
        return view('akademi.vitrin.edit', [
            'student' => $student,
            'vitrin' => $student->vitrin,
        ]);
    }

    /**
     * Update the vitrin.
     */
    public function update(Request $request)
    {
        $student = auth()->user()->student;
        $vitrin = $student->vitrin;

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:5000'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:2048'],
            'contact_info' => ['nullable', 'array'],
            'achievements' => ['nullable', 'array'],
            'publications' => ['nullable', 'array'],
            'certifications' => ['nullable', 'array'],
            'custom_sections' => ['nullable', 'array'],
            'template' => ['required', 'string', 'in:default,modern,minimal,academic'],
            'theme_settings' => ['required', 'array'],
            'seo_metadata' => ['nullable', 'array'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($vitrin->profile_photo) {
                Storage::delete($vitrin->profile_photo);
            }
            $profilePhoto = $request->file('profile_photo')->store('student-photos', 'public');
            $vitrin->profile_photo = $profilePhoto;
        }

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            if ($vitrin->cover_photo) {
                Storage::delete($vitrin->cover_photo);
            }
            $coverPhoto = $request->file('cover_photo')->store('student-covers', 'public');
            $vitrin->cover_photo = $coverPhoto;
        }

        $vitrin->update([
            'title' => $request->title,
            'tagline' => $request->tagline,
            'about' => $request->about,
            'contact_info' => $request->contact_info,
            'achievements' => $request->achievements,
            'publications' => $request->publications,
            'certifications' => $request->certifications,
            'custom_sections' => $request->custom_sections,
            'template' => $request->template,
            'theme_settings' => $request->theme_settings,
            'seo_metadata' => $request->seo_metadata,
        ]);

        return back()->with('success', 'Portfolio updated successfully.');
    }

    /**
     * Upload media to the vitrin.
     */
    public function uploadMedia(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'type' => ['required', 'string', 'in:case,certificate,publication'],
        ]);

        $path = $request->file('file')->store("student-{$request->type}s", 'public');

        return response()->json([
            'path' => $path,
            'url' => Storage::url($path),
        ]);
    }

    /**
     * Remove media from the vitrin.
     */
    public function removeMedia(Request $request)
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        if (Storage::exists($request->path)) {
            Storage::delete($request->path);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Preview the vitrin with a different template.
     */
    public function preview(Request $request)
    {
        $student = auth()->user()->student;
        $vitrin = $student->vitrin;

        return view('akademi.vitrin.preview', [
            'student' => $student,
            'vitrin' => $vitrin,
            'template' => $request->template ?? 'default',
        ]);
    }
} 