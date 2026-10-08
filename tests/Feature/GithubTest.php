<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GithubTest extends TestCase
{
    private function depot(string $nom, ?string $langage, int $taille = 10, bool $fork = false): array
    {
        return ['name' => $nom, 'description' => null, 'language' => $langage, 'html_url' => "https://github.com/moi/{$nom}",
            'stargazers_count' => 0, 'pushed_at' => '2026-10-08T10:00:00Z', 'fork' => $fork, 'archived' => false, 'size' => $taille];
    }

    public function test_le_profil_github_est_resume_et_mis_en_cache(): void
    {
        Cache::flush();
        Http::fake([
            'api.github.com/users/*/repos*' => Http::response([
                $this->depot('portfolio-api', 'PHP'),
                $this->depot('portfolio-web', 'JavaScript'),
                $this->depot('red-product', 'PHP'),
                $this->depot('vide', 'C', 0),
                $this->depot('copie', 'Go', 10, true),
            ]),
            'api.github.com/users/*' => Http::response(['login' => 'moi', 'name' => 'Moi', 'avatar_url' => 'https://a/x.png', 'html_url' => 'https://github.com/moi', 'followers' => 3]),
        ]);

        $this->getJson('/api/github')
            ->assertOk()
            ->assertJsonPath('depots_publics', 3) // dépôts vides et copies (fork) exclus
            ->assertJsonPath('langages.0', ['nom' => 'PHP', 'depots' => 2])
            ->assertJsonPath('langages.1', ['nom' => 'JavaScript', 'depots' => 1])
            ->assertJsonCount(3, 'depots');

        $this->getJson('/api/github')->assertOk();
        Http::assertSentCount(2); // le 2e appel vient du cache, GitHub n'est pas rappelé
    }

    public function test_si_github_est_en_panne_la_derniere_version_connue_est_servie(): void
    {
        Cache::flush();
        Cache::forever('github:aissatouAFI:secours', ['login' => 'aissatouAFI', 'depots' => []]);
        Http::fake(['*' => Http::response('panne', 500)]);

        $this->getJson('/api/github')->assertOk()->assertJsonPath('login', 'aissatouAFI');
    }

    public function test_si_github_est_en_panne_sans_version_connue_on_repond_503(): void
    {
        Cache::flush();
        Http::fake(['*' => Http::response('panne', 500)]);

        $this->getJson('/api/github')->assertStatus(503);
    }
}
