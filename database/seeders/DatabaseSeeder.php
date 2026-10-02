<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte admin de développement, créé uniquement en local.
        // En production, ce compte au mot de passe connu ne doit jamais exister :
        // l'admin se crée avec `php artisan make:filament-user`.
        if (app()->environment('local')) {
            User::firstOrCreate(
                ['email' => 'admin@monportfolio.com'],
                [
                    'name' => 'Admin',
                    'password' => Hash::make('change-moi-123'),
                ]
            );
        }

        $this->call([
            ProfilSeeder::class,
            ServiceSeeder::class,
            RealisationSeeder::class,
            CvSeeder::class,
        ]);
    }
}
