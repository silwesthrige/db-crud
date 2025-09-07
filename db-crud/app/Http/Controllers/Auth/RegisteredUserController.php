<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'pending',
        ]);

        // Create notification for admin about new registration
        \App\Models\Notification::create([
            'type' => 'info',
            'title' => 'New User Registration',
            'message' => "A new user '{$user->name}' has registered and is waiting for approval.",
            'user_id' => null, // System notification for all admins
            'is_read' => false,
        ]);

        return redirect()->route('login')->with('success', 
            'Registration successful! Your account is pending approval. You will be notified once approved.');
    }
}
