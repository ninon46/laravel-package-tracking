@component('mail::message')
# Votre Colis a été Expédié 📦

Bonjour {{ $package->recipient_name }},

Votre colis a été créé et est en attente d'expédition. Vous pouvez suivre votre livraison à tout moment.

**Numéro de Suivi:** {{ $trackingNumber }}

@component('mail::button', ['url' => $trackingLink])
Suivre mon Colis
@endcomponent

## Informations de Livraison

- **Adresse:** {{ $package->delivery_address }}
- **Ville:** {{ $package->city }}, {{ $package->postal_code }}
- **Statut:** {{ ucfirst(str_replace('_', ' ', $package->status)) }}

## Accès Rapide au Suivi

Vous pouvez également accéder au suivi en visitant:
{{ $trackingLink }}

Ou en utilisant le code QR fourni.

Cordialement,
L'Équipe de Livraison
@endcomponent
