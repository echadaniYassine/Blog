<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Show members on dashboard
    public function dashboard()
    {
        $members = User::all();
        return view('pages.members', compact('members'));
    }

    // Show user profile
    public function profile($id)
    {
        $user = User::findOrFail($id);
        return view('users.profile', compact('user'));
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.profile', compact('user'));
    }
    public function index()
    {
        $members = User::where('role', 'blogger')->get(); // only bloggers
        return view('members.index', compact('members'));
    }
  

    // Edit profile
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->id !== $user->id && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('users.edit', compact('user'));
    }


    // Update user profile
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        if (Auth::id() !== $user->id && Auth::user()->role !== 'admin') {
            return redirect()->route('profile.show', $id)->with('error', 'You are not authorized to update this profile.');
        }
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'];
    
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
    
            $user->profile_image = $request->file('profile_image')->store('profile_images', 'public');
        }
    
        $user->save();
    
        return redirect()->route('profile.show', $user->id)->with('success', 'Profile updated successfully!');
    }
}