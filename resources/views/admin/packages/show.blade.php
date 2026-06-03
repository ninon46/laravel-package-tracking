@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Détails du Colis</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning">Éditer</a>
            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informations du Colis</h5>
                </div>
                <div class="card-body">
                    <p><strong>Numéro de Suivi:</strong> <code>{{ $package->tracking_number }}</code></p>
                    <p><strong>Statut:</strong> 
                        @switch($package->status)
                            @case('pending')
                                <span class="badge bg-warning">En attente</span>
                                @break
                            @case('in_transit')
                                <span class="badge bg-info">En transit</span>
                                @break
                            @case('delivered')
                                <span class="badge bg-success">Livré</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">Annulé</span>
                                @break
                        @endswitch
                    </p>
                    <p><strong>Créé:</strong> {{ $package->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Expédié:</strong> {{ $package->shipped_at ? $package->shipped_at->format('d/m/Y H:i') : 'Non expédié' }}</p>
                    <p><strong>Livré:</strong> {{ $package->delivered_at ? $package->delivered_at->format('d/m/Y H:i') : 'Non livré' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Code QR</h5>
                </div>
                <div class="card-body text-center">
                    @if ($package->qr_code)
                        <img src="{{ $package->qr_code }}" alt="QR Code" style="max-width: 200px;">
                        <p class="mt-2 small text-muted">Scanner ce code pour suivre le colis</p>
                    @else
                        <p class="text-muted">Aucun code QR disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informations du Destinataire</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> {{ $package->recipient_name }}</p>
                    <p><strong>Email:</strong> {{ $package->recipient_email }}</p>
                    <p><strong>Téléphone:</strong> {{ $package->recipient_phone }}</p>
                    <p><strong>Adresse:</strong> {{ $package->delivery_address }}</p>
                    <p><strong>Ville:</strong> {{ $package->city }}</p>
                    <p><strong>Code Postal:</strong> {{ $package->postal_code }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.packages.resend-email', $package) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">📧 Renvoyer l'Email de Suivi</button>
                    </form>
                    <a href="{{ route('tracking.show', $package) }}" class="btn btn-info w-100 mb-2">🔍 Voir la Page Publique</a>
                    <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning w-100">✏️ Mettre à Jour le Statut</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Historique de Suivi</h5>
        </div>
        <div class="card-body">
            @if ($package->trackingHistories->count() > 0)
                <div class="timeline">
                    @foreach ($package->trackingHistories->reverse() as $history)
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker">
                                @switch($history->status)
                                    @case('pending')
                                        <span class="badge bg-warning">⏱️</span>
                                        @break
                                    @case('in_transit')
                                        <span class="badge bg-info">🚚</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success">✅</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">❌</span>
                                        @break
                                @endswitch
                            </div>
                            <div class="timeline-content">
                                <h6>{{ $history->description }}</h6>
                                <p class="text-muted small">
                                    {{ $history->created_at->format('d/m/Y H:i') }}
                                    @if ($history->location)
                                        - 📍 {{ $history->location }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Aucun historique disponible</p>
            @endif
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #ddd;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 30px;
        text-align: center;
    }
</style>
@endsection
