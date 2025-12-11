<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FarmSelectionController extends Controller
{
    public function index(Request $request)
    {
        $farms = $request->user()->farms;
        
        return Inertia::render('FarmSelection', [
            'farms' => $farms,
        ]);
    }

    public function select(Request $request, $farmId)
    {
        // Verify user has access to this farm
        $farm = $request->user()->farms()->find($farmId);
        
        if (!$farm) {
            return redirect()->route('admin.farms.selection')->with('error', 'You do not have access to this farm.');
        }
        
        // Store farm_id in session
        $request->session()->put('farm_id', $farmId);
        
        return redirect()->route('admin.dashboard');
    }
}
