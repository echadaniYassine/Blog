<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show Registration Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Register User

    public function register(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',  // Validation for phone
            'bio' => 'nullable|string',           // Validation for bio
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validation for img
            'address' => 'nullable|string',       // Validation for address
        ]);

        // Create a new user (without the image field)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,   // Assign phone number from the request
            'bio' => $request->bio,       // Assign bio from the request
            'address' => $request->address, // Assign address from the request
            'role' => 'blogger',           // Default role (you can modify this based on your needs)
        ]);

        // Check if the user uploaded an image
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->img = $imagePath;
        }

        // Save the user data, including the image path if available
        $user->save();

        // Log the user in after successful registration
        auth()->login($user);

        // Redirect to the home route with a success message
        return redirect()->route('home')->with('message', 'Registration successful');
    }


    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login User
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('home')->with('message', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    // Logout User
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('message', 'Logged out successfully!');
    }

    // Show User Profile
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.profile', compact('user'));
    }


    public function update(Request $request, $id)
    {
        // Find the user or fail
        $user = User::findOrFail($id);

        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Exclude current user's email from uniqueness check
            'password' => 'nullable|string|min:8|confirmed', // Password is optional
            'phone' => 'nullable|string|max:15',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string',
        ]);

        // Update the user attributes (except for password)
        $user->update($request->only(['name', 'email', 'phone', 'bio', 'address']));

        // Handle password update if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle image upload if a new image is provided
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->img = $imagePath;
        }

        // Save the updated user
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Profile updated successfully!');
    }

    // Show dashboard for authenticated user
    public function dashboard()
    {
        $user = auth()->user();

        return view('users.dashboard', compact('user'));
    }
}
