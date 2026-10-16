<?php

namespace Tests\Feature;

use App\Models\Realisation;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApiPubliqueTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_adresses_lues_par_le_site_repondent(): void
    {
        Service::create(['titre' => 'Backend', 'description' => 'API Laravel', 'actif' => true]);
        Service::create(['titre' => 'Caché', 'description' => 'Inactif', 'actif' => false]);
        Realisation::create(['titre' => 'AgriSn', 'slug' => 'agrisn', 'description' => 'Plateforme', 'technologies' => ['HTML']]);

        $this->getJson('/api/profil')->assertOk()->assertJsonStructure(['photo_url', 'titre_accroche', 'sous_titre', 'a_propos']);
        $this->getJson('/api/services')->assertOk()->assertJsonCount(1)->assertJsonPath('0.titre', 'Backend'); // les services inactifs sont masqués
        $this->getJson('/api/realisations')->assertOk()->assertJsonPath('0.technologies', ['HTML']);
        $this->getJson('/api/realisations/agrisn')->assertOk()->assertJsonPath('titre', 'AgriSn');
        $this->getJson('/api/realisations/inexistante')->assertNotFound();
        $this->getJson('/api/cv')->assertOk()->assertJsonStructure(['experiences', 'formations', 'competences', 'pdf']);
    }

    public function test_le_formulaire_de_contact_valide_enregistre_et_envoie_l_email(): void
    {
        Mail::fake();

        $this->postJson('/api/contact', [])->assertStatus(422);

        $this->postJson('/api/contact', ['nom' => 'Awa', 'email' => 'awa@example.com', 'message' => 'Bonjour'])
            ->assertCreated();

        $this->assertDatabaseHas('contact_messages', ['email' => 'awa@example.com']);
        Mail::assertSent(\App\Mail\NouveauMessageContact::class);
    }
}
