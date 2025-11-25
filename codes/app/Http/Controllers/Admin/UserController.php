<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('farms:id,name')
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_superadmin' => $user->is_superadmin,
                    'farms' => $user->farms->map(fn($farm) => $farm->name)->join(', '),
                    'farms_count' => $user->farms->count(),
                    'created_at' => $user->created_at->format('M d, Y'),
                ];
            });

        $farms = Farm::select('id', 'name')->get();

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'farms' => $farms,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'is_superadmin' => 'boolean',
            'farm_ids' => 'array',
            'farm_ids.*' => 'exists:farms,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_superadmin' => $validated['is_superadmin'] ?? false,
        ]);

        if (!empty($validated['farm_ids'])) {
            $user->farms()->attach($validated['farm_ids']);
        }

        return redirect()->back()->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'is_superadmin' => 'boolean',
            'farm_ids' => 'array',
            'farm_ids.*' => 'exists:farms,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_superadmin' => $validated['is_superadmin'] ?? false,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if (isset($validated['farm_ids'])) {
            $user->farms()->sync($validated['farm_ids']);
        }

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
