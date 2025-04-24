<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Vitrin;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class FindVitrinMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = explode('.', $request->getHost())[0];
        
        // Skip for main domain
        if ($subdomain === 'www' || $subdomain === 'hekimport') {
            return $next($request);
        }

        // Try to get vitrin from cache first
        $vitrin = Cache::remember("vitrin.{$subdomain}", 3600, function () use ($subdomain) {
            return Vitrin::where('subdomain', $subdomain)->first();
        });

        if (!$vitrin) {
            abort(404, 'Vitrin not found');
        }

        // Share vitrin with all views
        view()->share('vitrin', $vitrin);
        
        // Add vitrin to request for controller access
        $request->attributes->set('vitrin', $vitrin);

        return $next($request);
    }
} 