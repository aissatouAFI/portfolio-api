<?php

namespace Database\Seeders;

use App\Models\CvCompetence;
use Illuminate\Database\Seeder;

class CvSeeder extends Seeder
{
    /**
     * Seeder volontairement minimal pour les compétences (contenu neutre, technique).
     * Les expériences et formations sont personnelles : ajoute-les depuis l'admin,
     * je ne les invente pas ici.
     */
    public function run(): void
    {
        $competences = [
            ['nom' => 'Laravel', 'categorie' => 'backend', 'ordre' => 1],
            ['nom' => 'PHP', 'categorie' => 'backend', 'ordre' => 2],
            ['nom' => 'MySQL', 'categorie' => 'backend', 'ordre' => 3],
            ['nom' => 'PostgreSQL', 'categorie' => 'backend', 'ordre' => 4],
            ['nom' => 'React', 'categorie' => 'frontend', 'ordre' => 1],
            ['nom' => 'Tailwind CSS', 'categorie' => 'frontend', 'ordre' => 2],
            ['nom' => 'JavaScript', 'categorie' => 'frontend', 'ordre' => 3],
            ['nom' => 'Flutter', 'categorie' => 'frontend', 'ordre' => 4],
            ['nom' => 'Git & GitHub', 'categorie' => 'outils', 'ordre' => 1],
            ['nom' => 'Railway', 'categorie' => 'outils', 'ordre' => 2],
            ['nom' => 'Vercel', 'categorie' => 'outils', 'ordre' => 3],
        ];

        foreach ($competences as $competence) {
            CvCompetence::create($competence);
        }
    }
}
