<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Widgets\ChartWidget;

class MessagesChart extends ChartWidget
{
    protected static ?string $heading = 'Messages reçus (12 derniers mois)';

    protected static ?int $sort = 4;

    protected static string $color = 'warning';

    protected function getData(): array
    {
        $mois = collect(range(11, 0))->map(fn (int $i) => now()->startOfMonth()->subMonths($i));

        // Regroupement en PHP pour rester indépendant du moteur de base de données
        $parMois = ContactMessage::where('created_at', '>=', $mois->first())
            ->get(['created_at'])
            ->countBy(fn (ContactMessage $m) => $m->created_at->format('Y-m'));

        return [
            'datasets' => [
                [
                    'label' => 'Messages',
                    'data' => $mois->map(fn ($m) => $parMois[$m->format('Y-m')] ?? 0)->all(),
                ],
            ],
            'labels' => $mois->map(fn ($m) => $m->locale('fr')->translatedFormat('M y'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        // Des nombres entiers sur l'axe vertical (pas de « 0,5 message »)
        return ['scales' => ['y' => ['ticks' => ['precision' => 0]]]];
    }
}
