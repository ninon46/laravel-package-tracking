<?php

namespace App\Services;

use App\Models\Package;
use App\Models\TrackingHistory;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class PackageService
{
    /**
     * Crée un colis avec génération automatique de numéro de suivi et QR Code
     */
    public function createPackageWithTracking(array $data)
    {
        // Générer le numéro de suivi unique
        $trackingNumber = $this->generateUniqueTrackingNumber();

        // Créer le QR Code
        $qrCode = $this->generateQRCode($trackingNumber);

        // Créer le colis
        $package = Package::create([
            'tracking_number' => $trackingNumber,
            'qr_code' => $qrCode,
            'user_id' => auth()->id(),
            'recipient_name' => $data['recipient_name'],
            'recipient_email' => $data['recipient_email'],
            'recipient_phone' => $data['recipient_phone'],
            'delivery_address' => $data['delivery_address'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        // Créer l'historique initial
        TrackingHistory::create([
            'package_id' => $package->id,
            'status' => 'pending',
            'description' => 'Colis créé et en attente d\'expédition',
            'location' => null,
        ]);

        return $package;
    }

    /**
     * Génère un numéro de suivi unique
     */
    public function generateUniqueTrackingNumber(): string
    {
        do {
            $prefix = 'PKG';
            $timestamp = now()->format('YmdHis');
            $random = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $trackingNumber = $prefix . $timestamp . $random;
        } while (Package::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    /**
     * Génère un code QR pour le numéro de suivi
     */
    public function generateQRCode(string $trackingNumber): string
    {
        $qrCode = QrCode::create($trackingNumber);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        return $result->getDataUri();
    }

    /**
     * Met à jour le statut d'un colis et crée un historique
     */
    public function updatePackageStatus(Package $package, string $newStatus, string $location = null, string $description = null): void
    {
        $package->update(['status' => $newStatus]);

        $statusMessages = [
            'pending' => 'Colis en attente d\'expédition',
            'in_transit' => 'Colis en transit',
            'delivered' => 'Colis livré',
            'cancelled' => 'Colis annulé',
        ];

        TrackingHistory::create([
            'package_id' => $package->id,
            'status' => $newStatus,
            'description' => $description ?? $statusMessages[$newStatus] ?? 'Mise à jour du statut',
            'location' => $location,
        ]);
    }

    /**
     * Exporte les numéros de suivi en CSV
     */
    public function exportTrackingNumbersCSV(array $packageIds = [])
    {
        $query = Package::query();

        if (!empty($packageIds)) {
            $query->whereIn('id', $packageIds);
        }

        $packages = $query->get();
        $csv = "Numéro de suivi,Destinataire,Email,Téléphone,Adresse,Ville,Code Postal,Statut,Lien de suivi\n";

        foreach ($packages as $package) {
            $trackingLink = route('tracking.show', $package->id);
            $csv .= "\"" . $package->tracking_number . "\"";
            $csv .= ",\"" . $package->recipient_name . "\"";
            $csv .= ",\"" . $package->recipient_email . "\"";
            $csv .= ",\"" . $package->recipient_phone . "\"";
            $csv .= ",\"" . $package->delivery_address . "\"";
            $csv .= ",\"" . $package->city . "\"";
            $csv .= ",\"" . $package->postal_code . "\"";
            $csv .= ",\"" . $package->status . "\"";
            $csv .= ",\"" . $trackingLink . "\"\n";
        }

        return $csv;
    }
}
