<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip installation check for installation routes
        if ($request->is('install*')) {
            return $next($request);
        }

        // Skip installation check for API routes
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Skip installation check for Livewire routes
        if ($request->is('livewire/*')) {
            return $next($request);
        }

        // Skip installation check if explicitly disabled via config
        if (config('installation.skip', false)) {
            return $next($request);
        }

        // Force installation if configured
        if (config('installation.force', false)) {
            return redirect()->route('installation.index');
        }

        // Check if settings table exists
        if ( ! Schema::hasTable('settings')) {
            return redirect()->route('installation.index');
        }

        // Check if installation is completed
        $installationCompleted = settings('installation_completed', false);

        if ( ! $installationCompleted) {
            return redirect()->route('installation.index');
        }

        return $next($request);
    }
}
