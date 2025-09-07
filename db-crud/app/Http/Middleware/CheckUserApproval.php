<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Allow admin users to pass through
            if ($user->isAdmin()) {
                return $next($request);
            }
            
            // Check if user is approved and active
            if (!$user->isApproved()) {
                \Illuminate\Support\Facades\Auth::logout();
                
                $message = match($user->status) {
                    'pending' => 'Your account is pending approval. Please wait for admin approval.',
                    'rejected' => 'Your account has been rejected. Reason: ' . ($user->rejection_reason ?? 'No reason provided'),
                    'inactive' => 'Your account has been deactivated by the administrator. Please contact support.',
                    default => 'Your account status is not valid for login.'
                };
                
                return redirect()->route('login')->with('error', $message);
            }
        }
        
        return $next($request);
    }
}
