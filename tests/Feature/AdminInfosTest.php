<?php

namespace Tests\Feature;

use App\Filament\Resources;
use App\Models;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminInfosTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_bouton_infos_s_ouvre_sur_chaque_page(): void
    {
        $this->actingAs(Models\User::create(['name' => 'Test', 'email' => 't@example.com', 'password' => 'secret123']));

        $cas = [
            [Resources\RealisationResource\Pages\ListRealisations::class, Models\Realisation::create(['titre' => 'Projet', 'slug' => 'projet', 'description' => 'Desc', 'lien_demo' => 'https://example.com', 'technologies' => ['PHP', 'React']]), 'Projet'],
            [Resources\ServiceResource\Pages\ListServices::class, Models\Service::create(['titre' => 'Service A', 'description' => 'Desc service']), ['titre' => 'Service A']],
            [Resources\ContactMessageResource\Pages\ListContactMessages::class, Models\ContactMessage::create(['nom' => 'Awa', 'email' => 'awa@example.com', 'sujet' => 'Bonjour', 'message' => 'Message test']), ['message' => 'Message test']],
            [Resources\CvExperienceResource\Pages\ListCvExperiences::class, Models\CvExperience::create(['poste' => 'Stagiaire', 'entreprise' => 'Volkano', 'date_debut' => '2026-08-03']), ['entreprise' => 'Volkano']],
            [Resources\CvFormationResource\Pages\ListCvFormations::class, Models\CvFormation::create(['diplome' => 'Licence', 'etablissement' => 'AFI-UE', 'date_debut' => '2026-10-06']), ['etablissement' => 'AFI-UE']],
            [Resources\CvCompetenceResource\Pages\ListCvCompetences::class, Models\CvCompetence::create(['nom' => 'Laravel', 'categorie' => 'backend']), ['nom' => 'Laravel']],
            [Resources\CvPdfResource\Pages\ListCvPdfs::class, Models\CvPdf::create(['fichier' => 'cv/test.pdf', 'nom_original' => 'Mon CV', 'actif' => true]), ['nom_original' => 'Mon CV']],
        ];

        foreach ($cas as [$page, $record, $attendu]) {
            $test = Livewire::test($page)
                ->assertTableActionVisible('view', $record)
                ->mountTableAction('view', $record)
                ->assertHasNoTableActionErrors();

            // Réalisations : fiche d'infos (texte affiché) ; autres pages : formulaire en lecture seule
            is_array($attendu) ? $test->assertTableActionDataSet($attendu) : $test->assertSee($attendu);
        }
    }
}
