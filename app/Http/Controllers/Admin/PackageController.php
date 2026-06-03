<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\TrackingHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $packages = Package::with('user', 'trackingHistories')
            ->latest()
            ->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email',
            'recipient_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $package = new Package($validated);
        $package->user_id = auth()->id();
        $package->tracking_number = $package->generateTrackingNumber();
        $package->qr_code = $this->generateQRCode($package->tracking_number);
        $package->save();

        TrackingHistory::create([
            'package_id' => $package->id,
            'status' => 'pending',
            'description' => 'Colis créé et en attente d\'expédition',
            'location' => null,
        ]);

        return redirect()->route('admin.packages.show', $package)
            ->with('success', 'Colis créé avec succès');
    }

    public function show(Package $package)
    {
        $package->load('user', 'trackingHistories');
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_transit,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $package->update($validated);

        if ($request->has('status') && $request->status !== $package->getOriginal('status')) {
            $this->createTrackingHistory($package, $request);
        }

        return redirect()->route('admin.packages.show', $package)
            ->with('success', 'Colis mis à jour avec succès');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')
            ->with('success', 'Colis supprimé avec succès');
    }

    protected function generateQRCode($trackingNumber)
    {
        $qrCode = QrCode::create($trackingNumber);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $dataUri = $result->getDataUri();
        
        return $dataUri;
    }

    protected function createTrackingHistory(Package $package, Request $request)
    {
        $statusMessages = [
            'pending' => 'Colis en attente d\'expédition',
            'in_transit' => 'Colis en transit',
            'delivered' => 'Colis livré',
            'cancelled' => 'Colis annulé',
        ];

        TrackingHistory::create([
            'package_id' => $package->id,
            'status' => $request->status,
            'description' => $statusMessages[$request->status],
            'location' => $request->input('location'),
        ]);
    }
}
