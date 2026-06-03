@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Suivre Votre Colis 📦</h4>
                </div>

                <div class="card-body p-5">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-muted mb-4">Entrez votre numéro de suivi pour suivre votre colis en temps réel.</p>

                    <form action="{{ route('tracking.search') }}" method="POST">
                        @csrf

                        <div class="input-group input-group-lg mb-3">
                            <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" 
                                   name="tracking_number" placeholder="Ex: PKG20260603150255XXXX" 
                                   value="{{ old('tracking_number') }}" required autofocus>
                            <button class="btn btn-primary" type="submit">
                                🔍 Chercher
                            </button>
                            @error('tracking_number')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>

                    <hr>

                    <p class="text-muted small">
                        <strong>💡 Conseil:</strong> Vous pouvez scanner votre code QR avec votre téléphone pour accéder directement au suivi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
