<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $package = Package::where('tracking_number', $validated['tracking_number'])
            ->with('trackingHistories')
            ->first();

        if (!$package) {
            return back()->with('error', 'Colis non trouvé');
        }

        return view('tracking.show', compact('package'));
    }
}
