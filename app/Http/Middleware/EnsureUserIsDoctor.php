<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureUserIsDoctor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('EnsureUserIsDoctor middleware called', [
            'user' => $request->user(),
            'user_id' => $request->user() ? $request->user()->id : null,
            'role' => $request->user() ? $request->user()->role : null,
            'is_doctor' => $request->user() ? $request->user()->isDoctor() : false
        ]);

        if (!$request->user() || !$request->user()->isDoctor()) {
            Log::warning('User is not a doctor', [
                'user_id' => $request->user() ? $request->user()->id : null,
                'role' => $request->user() ? $request->user()->role : null
            ]);

            if ($request->user() && $request->user()->role === 'patient') {
                return redirect()->route('patient.dashboard');
            }
            return redirect('/');
        }

        return $next($request);
    }
} 