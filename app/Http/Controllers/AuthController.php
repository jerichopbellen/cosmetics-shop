<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class AuthController extends Controller
{
    // Show Forms
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    // Register Logic
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('avatar')) {
            $imagePath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'image_path' => $imagePath,
            'is_active' => true,
        ]);

        // Fire event to send the email
        event(new Registered($user));

        // DO NOT log them in here. 
        // Redirect them to a "Thank you, please check email" page or back to login.
        return redirect()->route('login')->with('success', 'Registration successful! Please check your email to verify your account before logging in.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, (bool) $request->input('remember'))) {
            $user = Auth::user();

            // Check 1: Is the account active?
            if (!$user || !$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }

            // Check 2: Strictly block if not verified
            if ($user instanceof MustVerifyEmailContract && !$user->hasVerifiedEmail()) {
                // LOGOUT immediately so they don't have an active session
                Auth::logout();
                
                // Redirect back to login with a specific message
                return redirect()->route('login')->with('verification_link', 'Your email is not verified. <a href="'.route('verification.resend.form').'" class="fw-bold text-decoration-underline">Click here to resend the link.</a>');
            }

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }
    public function showResendForm()
    {
        return view('auth.resend-verification');
    }

    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Your email is already verified. Please log in.');
        }

        event(new Registered($user));

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }
    // Logout Logic
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}