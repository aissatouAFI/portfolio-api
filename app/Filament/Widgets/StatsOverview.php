<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessageResource;
use App\Filament\Resources\CvCompetenceResource;
use App\Filament\Resources\CvExperienceResource;
use App\Filament\Resources\RealisationResource;
use App\Models\ContactMessage;
use App\Models\CvCompetence;
use App\Models\CvExperience;
use App\Models\CvFormation;
use App\Models\Realisation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $visites = $this->visitesParJour(30);
        $nonLus = ContactMessage::where('lu', false)->count();
        $parcours = CvExperience::count() + CvFormation::count();

        return [
            Stat::make('Visites aujourd\'hui', $visites->last())
                ->description($visites->sum() . ($visites->sum() > 1 ? ' visites' : ' visite') . ' sur 30 jours')
                ->descriptionIcon('heroicon-m-eye')
                ->chart($visites->all())
                ->color('success'),

            Stat::make('Messages non lus', $nonLus)
                ->description($nonLus > 0 ? 'À lire dans « Messages reçus »' : 'Tout est lu')
                ->descriptionIcon('heroicon-m-envelope')
                ->chart($this->creationsParMois(ContactMessage::class)->all())
                ->color('warning')
                ->url(ContactMessageResource::getUrl('index')),

            Stat::make('Messages reçus', ContactMessage::count())
                ->description('Le nombre total de messages')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->chart($this->cumulParMois(ContactMessage::class)->all())
                ->color('info')
                ->url(ContactMessageResource::getUrl('index')),

            Stat::make('Réalisations', Realisation::count())
                ->description(Realisation::where('en_avant', true)->count() . ' mises en avant')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->chart($this->cumulParMois(Realisation::class)->all())
                ->color('primary')
                ->url(RealisationResource::getUrl('index')),

            Stat::make('Compétences', CvCompetence::count())
                ->description('Le nombre total de compétences')
                ->descriptionIcon('heroicon-m-sparkles')
                ->chart($this->cumulParMois(CvCompetence::class)->all())
                ->color('danger')
                ->url(CvCompetenceResource::getUrl('index')),

            Stat::make('Parcours', $parcours)
                ->description(CvExperience::count() . ' expérience(s), ' . CvFormation::count() . ' formation(s)')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart($this->cumulParMois(CvExperience::class)->zip($this->cumulParMois(CvFormation::class))->map->sum()->all())
                ->color('gray')
                ->url(CvExperienceResource::getUrl('index')),
        ];
    }

    /** Visites des N derniers jours, jour par jour (0 les jours sans visite). */
    private function visitesParJour(int $jours): Collection
    {
        $dates = collect(range($jours - 1, 0))->map(fn (int $i) => now()->subDays($i)->toDateString());
        $parJour = DB::table('visites')->where('date', '>=', $dates->first())->pluck('total', 'date');

        return $dates->map(fn (string $date) => (int) ($parJour[$date] ?? 0));
    }

    /** Nombre d'éléments créés chaque mois sur les 12 derniers mois. */
    private function creationsParMois(string $modele): Collection
    {
        $mois = collect(range(11, 0))->map(fn (int $i) => now()->startOfMonth()->subMonths($i)->format('Y-m'));
        $parMois = $modele::where('created_at', '>=', now()->startOfMonth()->subMonths(11))
            ->get(['created_at'])
            ->countBy(fn ($element) => $element->created_at->format('Y-m'));

        return $mois->map(fn (string $m) => $parMois[$m] ?? 0);
    }

    /** Total cumulé à la fin de chaque mois sur les 12 derniers mois (courbe de progression). */
    private function cumulParMois(string $modele): Collection
    {
        $avant = $modele::where('created_at', '<', now()->startOfMonth()->subMonths(11))->count();
        $total = $avant;

        return $this->creationsParMois($modele)->map(function (int $nouveaux) use (&$total) {
            return $total += $nouveaux;
        });
    }
}
