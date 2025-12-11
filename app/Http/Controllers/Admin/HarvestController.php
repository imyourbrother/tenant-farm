<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Harvest;
use App\Models\Farm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HarvestController extends Controller
{
    public function index()
    {
        $harvests = Harvest::withoutGlobalScopes()
            ->with('farm:id,name')
            ->latest('harvested_at')
            ->paginate(20)
            ->through(function ($harvest) {
                return [
                    'id' => $harvest->id,
                    'crop_name' => $harvest->crop_name,
                    'quantity' => $harvest->quantity,
                    'farm_name' => $harvest->farm->name ?? 'Unknown',
                    'farm_id' => $harvest->farm_id,
                    'harvested_at' => $harvest->harvested_at->format('M d, Y'),
                    'created_at' => $harvest->created_at->format('M d, Y'),
                ];
            });

        $farms = Farm::select('id', 'name')->get();

        return Inertia::render('Admin/Harvests', [
            'harvests' => $harvests,
            'farms' => $farms,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'crop_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'harvested_at' => 'required|date',
        ]);

        Harvest::withoutGlobalScopes()->create($validated);

        return redirect()->back()->with('success', 'Harvest created successfully');
    }

    public function update(Request $request, $id)
    {
        $harvest = Harvest::withoutGlobalScopes()->findOrFail($id);

        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'crop_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'harvested_at' => 'required|date',
        ]);

        $harvest->update($validated);

        return redirect()->back()->with('success', 'Harvest updated successfully');
    }

    public function destroy($id)
    {
        $harvest = Harvest::withoutGlobalScopes()->findOrFail($id);
        $harvest->delete();

        return redirect()->back()->with('success', 'Harvest deleted successfully');
    }
}
