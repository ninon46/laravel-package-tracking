<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\TrackingHistory;
use App\Services\PackageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\TrackingLinkMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->packageService = $packageService;
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
            'send_email' => 'nullable|boolean',
        ]);

        // Créer le colis avec numéro de suivi et QR Code automatique
        $package = $this->packageService->createPackageWithTracking($validated);

        // Envoyer l'email au client si demandé
        if ($request->boolean('send_email')) {
            $this->sendTrackingEmail($package);
            $message = 'Colis créé et email de suivi envoyé avec succès';
        } else {
            $message = 'Colis créé avec succès';
        }

        return redirect()->route('admin.packages.show', $package)
            ->with('success', $message);
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
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $this->packageService->updatePackageStatus(
            $package,
            $validated['status'],
            $validated['location'] ?? null,
            $validated['description'] ?? null
        );

        return redirect()->route('admin.packages.show', $package)
            ->with('success', 'Colis mis à jour avec succès');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')
            ->with('success', 'Colis supprimé avec succès');
    }

    /**
     * Envoie le lien de suivi au client par email
     */
    public function sendTrackingEmail(Package $package)
    {
        Mail::to($package->recipient_email)->send(new TrackingLinkMail($package));
    }

    /**
     * Envoie un email de suivi à partir du dashboard
     */
    public function resendTrackingEmail(Request $request, Package $package)
    {
        $this->sendTrackingEmail($package);
        return back()->with('success', 'Email de suivi renvoyé avec succès');
    }

    /**
     * Exporte les numéros de suivi en CSV
     */
    public function exportCSV()
    {
        $csv = $this->packageService->exportTrackingNumbersCSV();

        $response = new StreamedResponse(function () use ($csv) {
            echo $csv;
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="tracking-numbers-' . now()->format('Y-m-d-His') . '.csv"');

        return $response;
    }

    /**
     * Envoie les emails de suivi en masse à plusieurs colis
     */
    public function bulkSendEmails(Request $request)
    {
        $validated = $request->validate([
            'package_ids' => 'required|array',
            'package_ids.*' => 'exists:packages,id',
        ]);

        $packages = Package::whereIn('id', $validated['package_ids'])->get();

        foreach ($packages as $package) {
            $this->sendTrackingEmail($package);
        }

        return back()->with('success', 'Emails de suivi envoyés à ' . count($packages) . ' client(s)');
    }
}
