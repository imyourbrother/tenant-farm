<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Farm::withCount(['users', 'harvests'])
            ->latest()
            ->get()
            ->map(function ($farm) {
                return [
                    'id' => $farm->id,
                    'name' => $farm->name,
                    'slug' => $farm->slug,
                    'users_count' => $farm->users_count,
                    'harvests_count' => $farm->harvests_count,
                    'created_at' => $farm->created_at->format('M d, Y'),
                ];
            });

        return Inertia::render('Admin/Farms', [
            'farms' => $farms,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:farms,slug',
        ]);

        Farm::create($validated);

        return redirect()->back()->with('success', 'Farm created successfully');
    }

    public function update(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:farms,slug,' . $farm->id,
        ]);

        $farm->update($validated);

        return redirect()->back()->with('success', 'Farm updated successfully');
    }

    public function destroy(Farm $farm)
    {
        $farm->delete();

        return redirect()->back()->with('success', 'Farm deleted successfully');
    }
}
