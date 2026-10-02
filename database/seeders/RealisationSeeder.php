<?php

namespace Database\Seeders;

use App\Models\Realisation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealisationSeeder extends Seeder
{
    /**
     * ⚠️ Pense à modifier les liens/images depuis l'admin une fois le CRUD branché.
     * Ce seeder sert de point de départ avec tes vrais projets.
     */
    public function run(): void
    {
        $realisations = [
            [
                'titre' => 'RED Product',
                'description' => 'Application de gestion hôtelière : authentification JWT, CRUD complet des hôtels avec upload photo, interface responsive suivant une maquette Figma dédiée.',
                'lien_demo' => 'https://red-product-web-beta.vercel.app',
                'lien_github' => 'https://github.com/aissatouAFI/red-product-web',
                'technologies' => ['Laravel', 'React', 'Tailwind CSS', 'MySQL', 'JWT'],
                'en_avant' => true,
                'ordre' => 1,
            ],
            [
                'titre' => 'TerangaConnect',
                'description' => 'Application de mise en relation entre clients et travailleurs, avec un parcours de recherche guidé et des formules d\'accès payantes.',
                'technologies' => ['Flutter', 'Laravel', 'PostgreSQL'],
                'en_avant' => true,
                'ordre' => 2,
            ],
        ];

        foreach ($realisations as $realisation) {
            $realisation['slug'] = Str::slug($realisation['titre']) . '-' . Str::random(5);
            Realisation::create($realisation);
        }
    }
}
