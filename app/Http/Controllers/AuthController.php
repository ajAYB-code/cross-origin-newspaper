<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user with hashed password
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }


    public function login(Request $request)
    {
        // Throttle repeated login attempts
        $maxAttempts = 5;
        $decayMinutes = 1;
        $key = 'login_attempt_' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return back()->withErrors(['email' => 'Too many login attempts. Please try again in a minute.']);
        }

        // Validate login inputs
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Prevent session fixation
            RateLimiter::clear($key);
            return redirect()->route('articles.index');
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
       $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
    }
