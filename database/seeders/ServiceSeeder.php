<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'titre' => 'Développement Backend',
                'description' => 'Conception d\'API robustes avec Laravel : authentification JWT, CRUD, logique métier, intégration de paiements et bien plus.',
                'icone' => 'server',
                'ordre' => 1,
            ],
            [
                'titre' => 'Développement Frontend',
                'description' => 'Création d\'interfaces modernes et réactives avec React, Tailwind CSS, consommation d\'API REST.',
                'icone' => 'code',
                'ordre' => 2,
            ],
            [
                'titre' => 'Déploiement & Mise en ligne',
                'description' => 'Déploiement d\'applications complètes (Railway, Vercel), configuration de bases de données et gestion de production.',
                'icone' => 'rocket',
                'ordre' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
