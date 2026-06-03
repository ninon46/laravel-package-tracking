@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Suivi de Votre Colis</h4>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Numéro de Suivi</h6>
                            <p class="display-6"><code>{{ $package->tracking_number }}</code></p>
                        </div>
                        <div class="col-md-6 text-center">
                            @if ($package->qr_code)
                                <h6>Code QR</h6>
                                <img src="{{ $package->qr_code }}" alt="QR Code" style="max-width: 150px;">
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info" role="alert">
                        <h5>
                            @switch($package->status)
                                @case('pending')
                                    ⏱️ Colis en Attente
                                    @break
                                @case('in_transit')
                                    🚚 Colis en Transit
                                    @break
                                @case('delivered')
                                    ✅ Colis Livré
                                    @break
                                @case('cancelled')
                                    ❌ Colis Annulé
                                    @break
                            @endswitch
                        </h5>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h6>Informations de Livraison</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Destinataire:</strong> {{ $package->recipient_name }}</p>
                            <p><strong>Adresse:</strong> {{ $package->delivery_address }}, {{ $package->city }} {{ $package->postal_code }}</p>
                            <p><strong>Créé:</strong> {{ $package->created_at->format('d/m/Y H:i') }}</p>
                            @if ($package->delivered_at)
                                <p><strong>Livré:</strong> {{ $package->delivered_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h6>Historique de Suivi</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach ($package->trackingHistories->reverse() as $history)
                                    <div class="timeline-item mb-4">
                                        <div class="row">
                                            <div class="col-auto">
                                                @switch($history->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning p-2" style="font-size: 1.2em;">⏱️</span>
                                                        @break
                                                    @case('in_transit')
                                                        <span class="badge bg-info p-2" style="font-size: 1.2em;">🚚</span>
                                                        @break
                                                    @case('delivered')
                                                        <span class="badge bg-success p-2" style="font-size: 1.2em;">✅</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger p-2" style="font-size: 1.2em;">❌</span>
                                                        @break
                                                @endswitch
                            </div>
                            <div class="col">
                                <h6 class="mb-1">{{ $history->description }}</h6>
                                <p class="text-muted small mb-0">{{ $history->created_at->format('d/m/Y à H:i') }}</p>
                                @if ($history->location)
                                    <p class="text-muted small">📍 {{ $history->location }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
</div>

<style>
    .timeline-item {
        border-left: 3px solid #ddd;
        padding-left: 20px;
    }

    .timeline-item:last-child {
        border-left: 3px solid #28a745;
    }
</style>
@endsection
