<?php

namespace Tests\Feature;

use App\Filament\Widgets;
use App\Models;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class TableauDeBordTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_compteur_de_visites_additionne_les_visites_du_jour(): void
    {
        $this->postJson('/api/visites')->assertCreated();
        $this->postJson('/api/visites')->assertCreated();

        $this->assertSame(1, DB::table('visites')->count());
        $this->assertSame(2, (int) DB::table('visites')->value('total'));
    }

    public function test_le_tableau_de_bord_s_affiche_sans_donnees(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin')->assertOk();
        Livewire::test(Widgets\Raccourcis::class)->assertSee('Raccourcis')->assertSee('Aucun CV en ligne');
        foreach ([Widgets\StatsOverview::class, Widgets\CompetencesChart::class, Widgets\TechnologiesChart::class, Widgets\VisitesChart::class, Widgets\MessagesChart::class, Widgets\DerniersMessages::class] as $widget) {
            Livewire::test($widget)->assertOk();
        }
    }

    public function test_les_widgets_affichent_les_donnees(): void
    {
        $this->actingAs($this->admin());

        DB::table('visites')->insert(['date' => now()->toDateString(), 'total' => 7, 'created_at' => now(), 'updated_at' => now()]);
        $message = Models\ContactMessage::create(['nom' => 'Awa', 'email' => 'awa@example.com', 'sujet' => 'Stage', 'message' => 'Bonjour Aissatou']);
        Models\Realisation::create(['titre' => 'Projet', 'slug' => 'projet', 'description' => 'Desc', 'en_avant' => true, 'technologies' => ['Laravel', 'React']]);
        Models\Realisation::create(['titre' => 'Projet 2', 'slug' => 'projet-2', 'description' => 'Desc', 'technologies' => ['Laravel']]);
        Models\CvCompetence::create(['nom' => 'Laravel', 'categorie' => 'backend']);
        Models\CvPdf::create(['fichier' => 'cv/cv.pdf', 'nom_original' => 'CV Aissatou', 'actif' => true]);

        $this->get('/admin')->assertOk();
        Livewire::test(Widgets\Raccourcis::class)
            ->assertSee('CV en ligne')->assertSee('CV Aissatou')->assertSee('Voir mon site')->assertSee('Ajouter une réalisation');

        Livewire::test(Widgets\StatsOverview::class)
            ->assertSee('Visites aujourd')->assertSee('7 visites sur 30 jours')->assertSee('Messages non lus')
            ->assertSee('Messages reçus')->assertSee('Réalisations')->assertSee('Compétences')->assertSee('Parcours');

        $technologies = Livewire::test(Widgets\TechnologiesChart::class)->instance();
        $donnees = (fn () => $this->getData())->call($technologies);
        $this->assertSame(['Laravel', 'React'], $donnees['labels']);
        $this->assertSame([2, 1], $donnees['datasets'][0]['data']);

        $competences = Livewire::test(Widgets\CompetencesChart::class)->instance();
        $this->assertSame([1, 0, 0, 0], (fn () => $this->getData())->call($competences)['datasets'][0]['data']);

        Livewire::test(Widgets\DerniersMessages::class)
            ->assertCanSeeTableRecords([$message])
            ->mountTableAction('view', $message)
            ->assertSee('Bonjour Aissatou');

        Livewire::test(Widgets\DerniersMessages::class)
            ->callTableAction('marquerLu', $message);

        $this->assertTrue($message->fresh()->lu);
    }

    private function admin(): Models\User
    {
        return Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'secret123']);
    }
}
