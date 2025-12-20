<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    // 1. Show the Profile Page
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // 2. Update the Profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed', // 'confirmed' checks password_confirmation field
        ]);

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password ONLY if the user typed something
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}