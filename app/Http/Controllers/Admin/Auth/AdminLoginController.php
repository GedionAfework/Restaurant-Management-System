<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminLoginController extends Controller
{
    // Show the admin login page
    public function showLoginForm()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            }
            Auth::logout(); // Log out if not admin
            return back()->withErrors(['email' => 'You are not authorized as an admin.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Logout (optional)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}