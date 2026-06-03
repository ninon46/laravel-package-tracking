<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrackingController extends Controller
{
    public function track($trackingNumber)
    {
        $package = Package::where('tracking_number', $trackingNumber)
            ->with('trackingHistories')
            ->first();

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Colis non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'status' => $package->status,
                'recipient_name' => $package->recipient_name,
                'delivery_address' => $package->delivery_address,
                'city' => $package->city,
                'postal_code' => $package->postal_code,
                'shipped_at' => $package->shipped_at,
                'delivered_at' => $package->delivered_at,
                'history' => $package->trackingHistories->map(fn($history) => [
                    'status' => $history->status,
                    'description' => $history->description,
                    'location' => $history->location,
                    'created_at' => $history->created_at,
                ]),
            ],
        ]);
    }

    public function scanQRCode(Request $request)
    {
        $validated = $request->validate([
            'qr_data' => 'required|string',
        ]);

        $package = Package::where('tracking_number', $validated['qr_data'])
            ->with('trackingHistories')
            ->first();

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Code QR invalide',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tracking_number' => $package->tracking_number,
                'status' => $package->status,
                'recipient_name' => $package->recipient_name,
            ],
        ]);
    }
}
