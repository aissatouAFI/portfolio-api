<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessageResource;
use App\Filament\Resources\CvCompetenceResource;
use App\Filament\Resources\RealisationResource;
use App\Models\ContactMessage;
use App\Models\CvCompetence;
use App\Models\CvExperience;
use App\Models\CvFormation;
use App\Models\Realisation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        // Visites des 30 derniers jours, jour par jour (0 les jours sans visite)
        $jours = collect(range(29, 0))->map(fn (int $i) => now()->subDays($i)->toDateString());
        $parJour = DB::table('visites')->where('date', '>=', $jours->first())->pluck('total', 'date');
        $serie = $jours->map(fn (string $jour) => (int) ($parJour[$jour] ?? 0));

        $nonLus = ContactMessage::where('lu', false)->count();

        return [
            Stat::make('Visites aujourd\'hui', $serie->last())
                ->description($serie->sum() . ' visites sur 30 jours')
                ->descriptionIcon('heroicon-m-eye')
                ->chart($serie->all())
                ->color('success'),

            Stat::make('Messages non lus', $nonLus)
                ->description(ContactMessage::count() . ' messages reçus au total')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($nonLus > 0 ? 'warning' : 'gray')
                ->url(ContactMessageResource::getUrl('index')),

            Stat::make('Réalisations', Realisation::count())
                ->description(Realisation::where('en_avant', true)->count() . ' mises en avant')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->url(RealisationResource::getUrl('index')),

            Stat::make('Compétences', CvCompetence::count())
                ->description(CvExperience::count() . ' expérience(s), ' . CvFormation::count() . ' formation(s)')
                ->descriptionIcon('heroicon-m-sparkles')
                ->url(CvCompetenceResource::getUrl('index')),
        ];
    }
}
