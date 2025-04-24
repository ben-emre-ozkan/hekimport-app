<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\SharedResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SharedResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Clinic $clinic)
    {
        $this->authorize('viewResources', $clinic);
        
        $query = $clinic->sharedResources();

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort functionality
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $resources = $query->paginate(10);

        return view('clinics.shared-resources.index', compact('clinic', 'resources'));
    }

    public function create(Clinic $clinic)
    {
        $this->authorize('shareResources', $clinic);
        
        return view('clinics.shared-resources.create', compact('clinic'));
    }

    public function store(Request $request, Clinic $clinic)
    {
        $this->authorize('shareResources', $clinic);
        
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(['file', 'document', 'calendar'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'array'],
            'file' => ['required_if:type,file', 'file', 'max:10240'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(['view', 'edit', 'delete', 'share'])],
        ]);

        $validated['shared_by'] = auth()->id();
        $validated['status'] = 'active';

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $validated['file_path'] = $file->store('shared-resources', 'public');
            $validated['file_type'] = $file->getMimeType();
            $validated['file_size'] = $file->getSize();
        }

        $resource = $clinic->sharedResources()->create($validated);

        // Share with specified users
        if ($request->has('users')) {
            foreach ($request->users as $userId) {
                $resource->sharedWith()->attach($userId, [
                    'permissions' => $validated['permissions'] ?? ['view'],
                ]);
            }
        }

        return redirect()->route('clinics.shared-resources.show', [$clinic, $resource])
            ->with('success', 'Resource shared successfully.');
    }

    public function show(Clinic $clinic, SharedResource $resource)
    {
        $this->authorize('viewResources', $clinic);
        
        if ($resource->clinic_id !== $clinic->id) {
            abort(404);
        }

        return view('clinics.shared-resources.show', compact('clinic', 'resource'));
    }

    public function edit(Clinic $clinic, SharedResource $resource)
    {
        $this->authorize('shareResources', $clinic);
        
        if ($resource->clinic_id !== $clinic->id) {
            abort(404);
        }

        return view('clinics.shared-resources.edit', compact('clinic', 'resource'));
    }

    public function update(Request $request, Clinic $clinic, SharedResource $resource)
    {
        $this->authorize('shareResources', $clinic);
        
        if ($resource->clinic_id !== $clinic->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'array'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(['view', 'edit', 'delete', 'share'])],
            'status' => ['required', 'string', Rule::in(['active', 'archived'])],
        ]);

        $resource->update($validated);

        return redirect()->route('clinics.shared-resources.show', [$clinic, $resource])
            ->with('success', 'Resource updated successfully.');
    }

    public function destroy(Clinic $clinic, SharedResource $resource)
    {
        $this->authorize('shareResources', $clinic);
        
        if ($resource->clinic_id !== $clinic->id) {
            abort(404);
        }

        // Delete file if exists
        if ($resource->file_path) {
            Storage::delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('clinics.shared-resources.index', $clinic)
            ->with('success', 'Resource deleted successfully.');
    }

    public function share(Request $request, Clinic $clinic, SharedResource $resource)
    {
        $this->authorize('shareResources', $clinic);
        
        if ($resource->clinic_id !== $clinic->id) {
            abort(404);
        }

        $validated = $request->validate([
            'users' => ['required', 'array'],
            'users.*' => ['exists:users,id'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['string', Rule::in(['view', 'edit', 'delete', 'share'])],
        ]);

        foreach ($validated['users'] as $userId) {
            $resource->sharedWith()->syncWithoutDetaching([
                $userId => ['permissions' => $validated['permissions']],
            ]);
        }

        return back()->with('success', 'Resource shared successfully.');
    }

    public function unshare(Request $request, Clinic $clinic, SharedResource $resource)
    {
        $this->authorize('shareResources', $clinic);
        
        if ($resource->clinic_id !== $clinic->id) {
            abort(404);
        }

        $validated = $request->validate([
            'users' => ['required', 'array'],
            'users.*' => ['exists:users,id'],
        ]);

        $resource->sharedWith()->detach($validated['users']);

        return back()->with('success', 'Resource unshared successfully.');
    }

    public function download(Clinic $clinic, SharedResource $resource)
    {
        $this->authorize('viewResources', $clinic);
        
        if ($resource->clinic_id !== $clinic->id) {
            abort(404);
        }

        if (!$resource->isFile()) {
            abort(400, 'This resource is not a file.');
        }

        if (!Storage::exists($resource->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($resource->file_path, $resource->title);
    }
} 