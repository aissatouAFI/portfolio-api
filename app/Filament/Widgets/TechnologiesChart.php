<?php

namespace App\Filament\Widgets;

use App\Models\Realisation;
use Filament\Widgets\ChartWidget;

class TechnologiesChart extends ChartWidget
{
    protected static ?string $heading = 'Technologies de mes réalisations';

    protected static ?string $description = 'Dans combien de projets chaque technologie est utilisée';

    protected static ?int $sort = 3;

    private const COULEURS = ['#ec4899', '#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#f97316', '#14b8a6', '#ef4444'];

    protected function getData(): array
    {
        // Les 8 technologies les plus utilisées, toutes réalisations confondues
        $technologies = Realisation::query()->pluck('technologies')
            ->flatten()
            ->filter()
            ->map(fn (string $t) => trim($t))
            ->countBy()
            ->sortDesc()
            ->take(8);

        return [
            'datasets' => [
                [
                    'label' => 'Projets',
                    'data' => $technologies->values()->all(),
                    'backgroundColor' => array_slice(self::COULEURS, 0, $technologies->count()),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $technologies->keys()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        // Un anneau n'a pas d'axes : on les masque (Filament les affiche par défaut)
        return [
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
            'plugins' => ['legend' => ['position' => 'bottom']],
            'cutout' => '60%',
        ];
    }
}
