<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VisitesChart extends ChartWidget
{
    protected static ?string $heading = 'Visites du site (30 derniers jours)';

    protected static ?int $sort = 3;

    protected static string $color = 'success';

    protected function getData(): array
    {
        $jours = collect(range(29, 0))->map(fn (int $i) => now()->subDays($i));
        $parJour = DB::table('visites')
            ->where('date', '>=', $jours->first()->toDateString())
            ->pluck('total', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Visites',
                    'data' => $jours->map(fn ($jour) => (int) ($parJour[$jour->toDateString()] ?? 0))->all(),
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $jours->map(fn ($jour) => $jour->format('d/m'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
