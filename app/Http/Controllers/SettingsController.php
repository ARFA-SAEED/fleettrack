<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    // Show settings page
    public function index()
    {
        $users = User::all();
        return view('settings.index', compact('users'));
    }

    // Add multiple users
    public function addUsers(Request $request)
    {
        $request->validate([
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.password' => 'required|string|min:6',
        ]);

        foreach ($request->users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'is_active' => true, // default active
            ]);
        }

        return redirect()->back()->with('success', 'Users added successfully!');
    }

    // Get user data for modal edit
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        // Prevent editing admin email if needed
        return response()->json($user);
    }

    // Update user
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent modifying protected admin
        if ($user->email === 'admin@admin.com') {
            return redirect()->back()->with('error', 'Admin account cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    // Delete user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting admin
        if ($user->email === 'admin@admin.com') {
            return redirect()->back()->with('error', 'Admin account cannot be deleted.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    // Pause / Activate user
    public function pauseUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent pausing admin
        if ($user->email === 'admin@admin.com') {
            return redirect()->back()->with('error', 'Admin account cannot be paused.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'User status updated!');
    }
}
