<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Show the registration form (via Inertia)
    public function showRegisterForm()
    {
        return inertia('auth/Register'); // Make sure you have the correct Inertia component path
    }

    // Handle the registration form submission
    public function register(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8', // password confirmation must match
        ]);

        // Create a new user in the database
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hash the password
        ]);

        // Log the user in after registration
        Auth::login($user);

        // Redirect to the dashboard or a different route
        return redirect()->route('Home');
    }
}
