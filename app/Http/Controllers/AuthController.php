<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Forms
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    // Register Logic
    public function register(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'role' => 'customer', // Default role we discussed
        ]);

        Auth::login($user);

        return redirect()->route('shop.index')->with('success', 'Welcome to GLOW!');
    }

    // Login Logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/products');
            }
            return redirect()->intended('/shop');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    // Logout Logic
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/shop');
    }
}