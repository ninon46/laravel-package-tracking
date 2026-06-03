# Système de Suivi de Colis Laravel

Un système complet de suivi de colis développé avec Laravel, incluant une interface administrateur, la génération de codes QR et une base de données MySQL.

## Fonctionnalités

### 👤 Authentification
- Authentification utilisateur sécurisée
- Rôles (Admin/Utilisateur)
- Inscription et connexion

### 📦 Gestion des Colis
- Création de colis
- Numéros de suivi uniques
- Statuts : Pending, In Transit, Delivered, Cancelled
- Historique complet de suivi

### 📱 Codes QR
- Génération automatique de codes QR
- Scan et suivi via QR Code
- Stockage des codes QR en base de données

### 👨‍💼 Espace Administrateur
- Tableau de bord des colis
- CRUD complet des colis
- Mise à jour des statuts
- Historique de suivi
- Gestion des utilisateurs

### 🔍 API REST
- Suivi de colis par numéro de suivi
- Scan de codes QR
- Endpoints sécurisés

## Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL 5.7+
- Node.js et npm (optionnel pour assets)

### Étapes d'installation

1. Cloner le repository
```bash
git clone https://github.com/ninon46/laravel-package-tracking.git
cd laravel-package-tracking
```

2. Installer les dépendances
```bash
composer install
```

3. Copier le fichier .env
```bash
cp .env.example .env
```

4. Générer la clé de l'application
```bash
php artisan key:generate
```

5. Configurer la base de données
```bash
# Éditer .env et configurer:
# DB_DATABASE=package_tracking
# DB_USERNAME=root
# DB_PASSWORD=votre_mot_de_passe
```

6. Exécuter les migrations
```bash
php artisan migrate
```

7. Créer un utilisateur admin (optionnel)
```bash
php artisan tinker
```

Dans la console Tinker :
```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

8. Lancer le serveur
```bash
php artisan serve
```

L'application sera accessible à `http://localhost:8000`

## Structure de la Base de Données

### Table: users
- id
- name
- email
- password
- role (admin/user)
- created_at, updated_at

### Table: packages
- id
- tracking_number (unique)
- qr_code
- user_id (FK)
- recipient_name
- recipient_email
- recipient_phone
- delivery_address
- city
- postal_code
- status (pending/in_transit/delivered/cancelled)
- shipped_at
- delivered_at
- notes
- created_at, updated_at

### Table: tracking_histories
- id
- package_id (FK)
- status
- description
- location
- created_at

## Utilisation

### Suivi Public
- Accédez à `/track`
- Entrez le numéro de suivi
- Consultez l'historique de suivi

### Admin Dashboard
- Accédez à `/admin/packages`
- Créez, modifiez, supprimez des colis
- Suivez les statuts en temps réel

### API REST
- `GET /api/track/{trackingNumber}` - Suivi par numéro
- `POST /api/scan-qr` - Scan de QR Code

## Technologies Utilisées

- **Laravel 11** - Framework PHP
- **MySQL** - Base de données
- **Endroid QR Code** - Génération de codes QR
- **Blade** - Template engine
- **Bootstrap** - Interface utilisateur

## Licence

MIT License

## Auteur

ninon46
