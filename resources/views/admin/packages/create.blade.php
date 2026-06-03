@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Créer un Nouveau Colis</h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.packages.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="recipient_name" class="form-label">Nom du Destinataire</label>
                            <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
                                   id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" required>
                            @error('recipient_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recipient_email" class="form-label">Email du Destinataire</label>
                            <input type="email" class="form-control @error('recipient_email') is-invalid @enderror" 
                                   id="recipient_email" name="recipient_email" value="{{ old('recipient_email') }}" required>
                            @error('recipient_email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recipient_phone" class="form-label">Téléphone du Destinataire</label>
                            <input type="tel" class="form-control @error('recipient_phone') is-invalid @enderror" 
                                   id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" required>
                            @error('recipient_phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Adresse de Livraison</label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                      id="delivery_address" name="delivery_address" rows="3" required>{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Code Postal</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                @error('postal_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="send_email" name="send_email" value="1" checked>
                            <label class="form-check-label" for="send_email">
                                Envoyer l'email de suivi au client
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Créer le Colis</button>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
