<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPatient
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'patient') {
            if ($request->user() && $request->user()->role === 'doctor') {
                return redirect()->route('doctor.dashboard');
            }
            return redirect('/');
        }

        return $next($request);
    }
} 