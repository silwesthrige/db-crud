<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Check if user is approved
            if (!$user->isAdmin() && !$user->isApproved()) {
                Auth::logout();
                
                $message = match($user->status) {
                    'pending' => 'Your account is pending approval. Please wait for admin approval.',
                    'rejected' => 'Your account has been rejected. Reason: ' . $user->rejection_reason,
                    default => 'Your account status is not valid for login.'
                };
                
                throw ValidationException::withMessages(['email' => $message]);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // Store user name for success message
        $userName = Auth::user() ? Auth::user()->name : 'User';
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', "Goodbye {$userName}! You have been successfully logged out.");
    }
}
