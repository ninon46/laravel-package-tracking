@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Gestion des Colis</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">+ Créer un Colis</a>
            <a href="{{ route('admin.packages.export-csv') }}" class="btn btn-success">📥 Exporter CSV</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Numéro de Suivi</th>
                    <th>Destinataire</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Créé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($packages as $package)
                    <tr>
                        <td>
                            <strong>{{ $package->tracking_number }}</strong>
                        </td>
                        <td>{{ $package->recipient_name }}</td>
                        <td>{{ $package->recipient_email }}</td>
                        <td>
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
                        </td>
                        <td>{{ $package->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.packages.show', $package) }}" class="btn btn-sm btn-info">Voir</a>
                            <form action="{{ route('admin.packages.resend-email', $package) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Renvoyer l\'email?')">📧</button>
                            </form>
                            <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-warning">Éditer</a>
                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $packages->links() }}
</div>
@endsection
