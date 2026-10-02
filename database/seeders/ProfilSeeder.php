<?php

namespace Database\Seeders;

use App\Models\Profil;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    /**
     * Lancer isolément avec :
     * php artisan db:seed --class=ProfilSeeder
     * (ne touche à aucune autre table, contrairement à db:seed seul)
     */
    public function run(): void
    {
        Profil::firstOrCreate([], [
            'titre_accroche' => 'Je construis des systèmes qui mettent des gens en relation.',
            'sous_titre' => "Développeuse backend & frontend, je conçois des API Laravel et des interfaces React — de la logique métier jusqu'à l'écran que les gens touchent vraiment.",
        ]);
    }
}
