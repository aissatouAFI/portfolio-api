# Portfolio API — Backend Laravel

Ce dossier contient le **code métier** (migrations, modèles, contrôleurs, routes, mail, seeders)
à intégrer dans un projet Laravel. Comme je n'ai pas accès à `composer`/Packagist depuis cet
environnement, voici la marche à suivre exacte pour tout assembler en local.

## 1. Créer le projet Laravel (si ce n'est pas déjà fait)

```bash
composer create-project laravel/laravel portfolio-api
cd portfolio-api
composer require tymon/jwt-auth
composer require laravel/sanctum   # optionnel si tu utilises déjà JWT partout
```

## 2. Copier les fichiers de ce dossier

Copie chaque fichier vers l'emplacement correspondant dans ton projet Laravel fraîchement créé :

| Fichier ici | Destination |
|---|---|
| `database/migrations/*.php` | `database/migrations/` |
| `database/seeders/*.php` | `database/seeders/` |
| `app/Models/*.php` (sauf User.php, à fusionner) | `app/Models/` |
| `app/Models/User.php` | **Remplace** ton `app/Models/User.php` existant |
| `app/Http/Controllers/Api/*.php` | `app/Http/Controllers/Api/` (crée le dossier `Api`) |
| `app/Mail/NouveauMessageContact.php` | `app/Mail/` |
| `resources/views/emails/*.blade.php` | `resources/views/emails/` |
| `routes/api.php` | **Remplace** `routes/api.php` |
| `config/cors.php` | **Remplace** `config/cors.php` |
| `.env.example` | Fusionne avec ton `.env` |

⚠️ **Ne copie pas** `config/mail-to-address-snippet.php` tel quel — c'est juste une note,
lis-le et ajoute la ligne indiquée dans ton vrai `config/mail.php`.

## 3. Configurer JWT

```bash
php artisan jwt:secret
```

Dans `config/auth.php`, assure-toi que le guard `api` utilise le driver `jwt` :

```php
'guards' => [
    'web' => ['driver' => 'session', 'provider' => 'users'],
    'api' => ['driver' => 'jwt', 'provider' => 'users'],
],
```

## 4. Configurer le stockage des fichiers (images + CV PDF)

```bash
php artisan storage:link
```

## 5. Configurer Gmail SMTP

1. Active la validation en 2 étapes sur ton compte Google (obligatoire).
2. Génère un **mot de passe d'application** ici : https://myaccount.google.com/apppasswords
3. Renseigne les variables `MAIL_*` dans `.env` (voir `.env.example`) avec ce mot de passe,
   **pas** ton mot de passe Gmail normal.

## 6. Base de données

Crée une base `portfolio` dans phpMyAdmin (XAMPP), puis :

```bash
php artisan migrate
php artisan db:seed
```

Cela crée :
- Un compte admin : `admin@monportfolio.com` / `change-moi-123` **(à changer immédiatement)**
- Des services, réalisations (RED Product, TerangaConnect) et compétences de démonstration

## 7. Lancer le serveur

```bash
php artisan serve
```

L'API est disponible sur `http://localhost:8000/api`.

## Endpoints principaux

**Publics (consommés par le site) :**
- `GET /api/services`
- `GET /api/realisations`
- `GET /api/realisations/{slug}`
- `GET /api/cv`
- `POST /api/contact`

**Admin (JWT requis, header `Authorization: Bearer {token}`) :**
- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`
- CRUD complet sous `/api/admin/services`, `/api/admin/realisations`,
  `/api/admin/cv-experiences`, `/api/admin/cv-formations`, `/api/admin/cv-competences`,
  `/api/admin/cv-pdf`, `/api/admin/contact-messages`

## Prochaine étape

Une fois ce backend fonctionnel (teste avec Postman/Insomnia : login admin, puis
un `GET /api/services`), on attaque le frontend React qui viendra consommer cette API.
