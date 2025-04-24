<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('clinic');
    }

    public function index(Request $request)
    {
        $query = Task::query()->with(['personnel', 'assigner']);

        // Filter by personnel
        if ($request->has('personnel_id')) {
            $query->where('personnel_id', $request->personnel_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by due date
        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Sort functionality
        $sortField = $request->get('sort', 'due_date');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $tasks = $query->paginate(10);
        $personnel = Personnel::where('status', 'active')->get();

        return view('clinic.tasks.index', compact('tasks', 'personnel'));
    }

    public function create()
    {
        $personnel = Personnel::where('status', 'active')->get();
        return view('clinic.tasks.create', compact('personnel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'personnel_id' => ['required', 'exists:personnel,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'string', Rule::in(['high', 'medium', 'low'])],
            'status' => ['required', 'string', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'array'],
        ]);

        $validated['assigned_by'] = auth()->id();

        $task = Task::create($validated);

        return redirect()->route('clinic.tasks.show', $task)
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        
        return view('clinic.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        
        $personnel = Personnel::where('status', 'active')->get();
        return view('clinic.tasks.edit', compact('task', 'personnel'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        
        $validated = $request->validate([
            'personnel_id' => ['required', 'exists:personnel,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', 'string', Rule::in(['high', 'medium', 'low'])],
            'status' => ['required', 'string', Rule::in(['pending', 'in_progress', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'array'],
        ]);

        $task->update($validated);

        return redirect()->route('clinic.tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        return redirect()->route('clinic.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function complete(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->markAsCompleted();

        return back()->with('success', 'Task marked as completed.');
    }

    public function export(Request $request)
    {
        $query = Task::query()->with(['personnel', 'assigner']);

        // Apply filters
        if ($request->has('personnel_id')) {
            $query->where('personnel_id', $request->personnel_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->get();

        $csv = \League\Csv\Writer::createFromString('');
        $csv->insertOne(['ID', 'Title', 'Description', 'Due Date', 'Priority', 'Status', 'Assigned To', 'Assigned By', 'Completed At', 'Created At']);

        foreach ($tasks as $task) {
            $csv->insertOne([
                $task->id,
                $task->title,
                $task->description,
                $task->due_date->format('Y-m-d H:i:s'),
                $task->priority,
                $task->status,
                $task->personnel->name,
                $task->assigner->name,
                $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : '',
                $task->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        return response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tasks.csv"',
        ]);
    }
} 