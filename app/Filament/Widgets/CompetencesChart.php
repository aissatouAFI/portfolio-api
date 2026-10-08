<?php

namespace App\Filament\Widgets;

use App\Models\CvCompetence;
use Filament\Widgets\ChartWidget;

class CompetencesChart extends ChartWidget
{
    protected static ?string $heading = 'Compétences par catégorie';

    protected static ?string $description = 'Nombre de compétences dans chaque domaine';

    protected static ?int $sort = 2;

    private const CATEGORIES = [
        'backend' => ['Backend', '#3b82f6'],
        'frontend' => ['Frontend', '#f59e0b'],
        'outils' => ['Outils', '#10b981'],
        'autre' => ['Autre', '#a855f7'],
    ];

    protected function getData(): array
    {
        $parCategorie = CvCompetence::query()->pluck('categorie')->countBy();
        $categories = collect(self::CATEGORIES);

        return [
            'datasets' => [
                [
                    'label' => 'Compétences',
                    'data' => $categories->keys()->map(fn (string $c) => $parCategorie[$c] ?? 0)->all(),
                    'backgroundColor' => $categories->map(fn (array $c) => $c[1] . 'cc')->values()->all(),
                    'borderColor' => $categories->map(fn (array $c) => $c[1])->values()->all(),
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $categories->map(fn (array $c) => $c[0])->values()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['ticks' => ['precision' => 0]]],
        ];
    }
}
