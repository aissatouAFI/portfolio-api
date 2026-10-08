<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class GithubController extends Controller
{
    /**
     * GET /api/github (public)
     * Profil et dépôts publics GitHub, mis en cache une heure : le site reste rapide
     * et ne dépasse jamais la limite d'appels de GitHub, même avec beaucoup de visiteurs.
     */
    public function index()
    {
        $utilisateur = config('services.github.username');

        try {
            $donnees = Cache::remember("github:{$utilisateur}", now()->addHour(), fn () => $this->charger($utilisateur));
        } catch (Throwable $e) {
            report($e);

            // GitHub indisponible : on renvoie la dernière version connue si elle existe
            $donnees = Cache::get("github:{$utilisateur}:secours");
            if (! $donnees) {
                return response()->json(['message' => 'GitHub est momentanément indisponible.'], 503);
            }
        }

        return response()->json($donnees);
    }

    private function charger(string $utilisateur): array
    {
        $github = Http::withHeaders(['User-Agent' => 'portfolio-aissatou-marone', 'Accept' => 'application/vnd.github+json'])
            ->timeout(8)
            ->baseUrl('https://api.github.com');

        $profil = $github->get("/users/{$utilisateur}")->throw()->json();
        $depots = collect($github->get("/users/{$utilisateur}/repos", ['per_page' => 100, 'sort' => 'pushed'])->throw()->json())
            ->reject(fn (array $d) => $d['fork'] || $d['archived'] || $d['size'] === 0);

        $donnees = [
            'login' => $profil['login'],
            'nom' => $profil['name'] ?: $profil['login'],
            'avatar' => $profil['avatar_url'],
            'url' => $profil['html_url'],
            'depots_publics' => $depots->count(),
            'abonnes' => $profil['followers'],
            // Part de chaque langage dans mes dépôts (langage principal de chaque dépôt)
            'langages' => $depots->pluck('language')->filter()->countBy()->sortDesc()
                ->map(fn (int $n, string $langage) => ['nom' => $langage, 'depots' => $n])
                ->values(),
            'depots' => $depots->take(4)->map(fn (array $d) => [
                'nom' => $d['name'],
                'description' => $d['description'],
                'langage' => $d['language'],
                'url' => $d['html_url'],
                'etoiles' => $d['stargazers_count'],
                'mis_a_jour' => $d['pushed_at'],
            ])->values(),
        ];

        Cache::forever("github:{$utilisateur}:secours", $donnees);

        return $donnees;
    }
}
