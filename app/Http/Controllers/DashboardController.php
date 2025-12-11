<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Farm;
use App\Models\Harvest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isSuperadmin = $user->is_superadmin;
        $currentFarmId = session('farm_id');
        
        if ($isSuperadmin) {
            // Superadmin Dashboard - Global Statistics
            $stats = [
                'total_farms' => Farm::count(),
                'total_users' => User::count(),
                'total_harvests' => Harvest::withoutGlobalScopes()->count(),
                'total_quantity' => Harvest::withoutGlobalScopes()->sum('quantity'),
            ];
            
            // Recent harvests across all farms
            $recentHarvests = Harvest::withoutGlobalScopes()
                ->with('farm:id,name')
                ->latest('harvested_at')
                ->take(10)
                ->get()
                ->map(function ($harvest) {
                    return [
                        'id' => $harvest->id,
                        'crop_name' => $harvest->crop_name,
                        'quantity' => $harvest->quantity,
                        'harvested_at' => $harvest->harvested_at->format('M d, Y'),
                        'farm_name' => $harvest->farm->name ?? 'Unknown',
                    ];
                });
            
            // Farms list with harvest counts
            $farms = Farm::withCount('harvests')
                ->get()
                ->map(function ($farm) {
                    return [
                        'id' => $farm->id,
                        'name' => $farm->name,
                        'slug' => $farm->slug,
                        'harvests_count' => $farm->harvests_count,
                    ];
                });
            
            return Inertia::render('Dashboard', [
                'isSuperadmin' => true,
                'stats' => $stats,
                'recentHarvests' => $recentHarvests,
                'farms' => $farms,
            ]);
        } else {
            // Farm Manager Dashboard - Farm-specific Statistics
            $currentFarm = Farm::find($currentFarmId);
            
            $stats = [
                'total_harvests' => Harvest::count(),
                'total_quantity' => Harvest::sum('quantity'),
                'recent_harvests_count' => Harvest::where('harvested_at', '>=', now()->subDays(7))->count(),
            ];
            
            // Recent harvests for this farm
            $recentHarvests = Harvest::latest('harvested_at')
                ->take(10)
                ->get()
                ->map(function ($harvest) {
                    return [
                        'id' => $harvest->id,
                        'crop_name' => $harvest->crop_name,
                        'quantity' => $harvest->quantity,
                        'harvested_at' => $harvest->harvested_at->format('M d, Y'),
                    ];
                });
            
            // Harvest summary by crop
            $harvestsByCrop = Harvest::selectRaw('crop_name, SUM(quantity) as total_quantity, COUNT(*) as count')
                ->groupBy('crop_name')
                ->get()
                ->map(function ($item) {
                    return [
                        'crop_name' => $item->crop_name,
                        'total_quantity' => $item->total_quantity,
                        'count' => $item->count,
                    ];
                });
            
            return Inertia::render('Dashboard', [
                'isSuperadmin' => false,
                'currentFarm' => $currentFarm,
                'stats' => $stats,
                'recentHarvests' => $recentHarvests,
                'harvestsByCrop' => $harvestsByCrop,
            ]);
        }
    }
}
