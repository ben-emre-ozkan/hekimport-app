<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDoctor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'doctor') {
            if ($request->user() && $request->user()->role === 'patient') {
                return redirect()->route('patient.dashboard');
            }
            return redirect('/');
        }

        return $next($request);
    }
} 