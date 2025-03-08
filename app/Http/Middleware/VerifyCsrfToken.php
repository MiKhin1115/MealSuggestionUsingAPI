<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
    
    /**
     * Handle CSRF token mismatches more gracefully
     */
    protected function handleTokenMismatch($request)
    {
        // Log the CSRF token mismatch
        \Illuminate\Support\Facades\Log::warning('CSRF token mismatch', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'intended_url' => $request->url()
        ]);
        
        // If it's an AJAX request, return a JSON response
        if ($request->ajax()) {
            return response()->json([
                'error' => 'Your session has expired. Please refresh the page and try again.'
            ], 419);
        }
        
        // Flash a message for the next request
        session()->flash('error', 'Your session has expired. Please try again.');
        
        // Regenerate the token
        session()->regenerateToken();
        
        // Redirect back with input
        return redirect()->back()->withInput($request->except('_token'));
    }
} 