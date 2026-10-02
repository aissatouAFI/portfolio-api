<?php

namespace Database\Seeders;

use App\Models\CvCompetence;
use Illuminate\Database\Seeder;

class CvCompetencesSeeder extends Seeder
{
    /**
     * Ajoute les nouvelles compétences (langages) sans dupliquer celles déjà
     * présentes. Peut être relancé sans risque :
     * php artisan db:seed --class=CvCompetencesSeeder
     */
    public function run(): void
    {
        $competences = [
            ['nom' => 'Laravel', 'categorie' => 'backend'],
            ['nom' => 'PHP', 'categorie' => 'backend'],
            ['nom' => 'Python', 'categorie' => 'backend'],
            ['nom' => 'Java', 'categorie' => 'backend'],
            ['nom' => 'MySQL', 'categorie' => 'backend'],
            ['nom' => 'React', 'categorie' => 'frontend'],
            ['nom' => 'JavaScript', 'categorie' => 'frontend'],
            ['nom' => 'HTML', 'categorie' => 'frontend'],
            ['nom' => 'CSS', 'categorie' => 'frontend'],
        ];

        foreach ($competences as $competence) {
            CvCompetence::firstOrCreate(
                ['nom' => $competence['nom']],
                ['categorie' => $competence['categorie']],
            );
        }
    }
}
