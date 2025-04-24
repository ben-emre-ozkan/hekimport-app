<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except(['register', 'store']);
    }

    /**
     * Show the student registration form.
     */
    public function register()
    {
        return view('akademi.auth.register');
    }

    /**
     * Store a newly created student account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'university' => ['required', 'string', 'max:255'],
            'graduation_year' => ['required', 'integer', 'min:2024', 'max:2030'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'subdomain' => [
                'required',
                'string',
                'max:50',
                'unique:students',
                'regex:/^[a-z0-9-]+$/',
            ],
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => 'student',
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'university' => $request->university,
                'graduation_year' => $request->graduation_year,
                'student_id' => $request->student_id,
                'subdomain' => $request->subdomain,
                'privacy_settings' => [
                    'profile_visibility' => false,
                    'email_visibility' => false,
                    'phone_visibility' => false,
                ],
            ]);

            // Create default vitrin
            $student->vitrin()->create([
                'title' => $user->name . "'s Portfolio",
                'template' => 'default',
                'theme_settings' => [
                    'colors' => [
                        'primary' => '#4F46E5',
                        'secondary' => '#7C3AED',
                        'accent' => '#EC4899',
                    ],
                ],
            ]);

            event(new \Illuminate\Auth\Events\Registered($user));

            auth()->login($user);

            return redirect()->route('akademi.dashboard')
                ->with('success', 'Welcome to Hekimport Akademi! Please verify your email address.');
        });
    }

    /**
     * Display the student dashboard.
     */
    public function dashboard()
    {
        $student = auth()->user()->student;
        
        return view('akademi.dashboard', [
            'student' => $student,
            'vitrin' => $student->vitrin,
            'mentorRequests' => $student->mentorRequests()->latest()->take(5)->get(),
            'resources' => $student->resources()->latest()->take(5)->get(),
        ]);
    }

    /**
     * Show the form for editing the student profile.
     */
    public function edit()
    {
        $student = auth()->user()->student;
        
        return view('akademi.profile.edit', [
            'student' => $student,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the student profile.
     */
    public function update(Request $request)
    {
        $student = auth()->user()->student;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'graduation_year' => ['required', 'integer', 'min:2024', 'max:2030'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'interests' => ['nullable', 'array'],
            'skills' => ['nullable', 'array'],
            'social_links' => ['nullable', 'array'],
            'privacy_settings' => ['required', 'array'],
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name' => $request->name,
            ]);

            $student->update([
                'university' => $request->university,
                'graduation_year' => $request->graduation_year,
                'student_id' => $request->student_id,
                'bio' => $request->bio,
                'interests' => $request->interests,
                'skills' => $request->skills,
                'social_links' => $request->social_links,
                'privacy_settings' => $request->privacy_settings,
            ]);
        });

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the student directory.
     */
    public function directory(Request $request)
    {
        $query = Student::query()
            ->with('user')
            ->verified()
            ->public();

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })->orWhere('university', 'like', "%{$request->search}%");
        }

        if ($request->university) {
            $query->where('university', $request->university);
        }

        if ($request->graduation_year) {
            $query->where('graduation_year', $request->graduation_year);
        }

        $students = $query->latest()->paginate(12);

        return view('akademi.directory', [
            'students' => $students,
            'universities' => Student::select('university')->distinct()->pluck('university'),
            'graduationYears' => Student::select('graduation_year')
                ->distinct()
                ->orderBy('graduation_year')
                ->pluck('graduation_year'),
        ]);
    }
} 