<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageProfil;
use App\Filament\Resources\CvPdfResource;
use App\Filament\Resources\RealisationResource;
use App\Models\CvPdf;
use Filament\Widgets\Widget;

class Raccourcis extends Widget
{
    protected static string $view = 'filament.widgets.raccourcis';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $origines = collect(config('cors.allowed_origins'));

        return [
            'ajouterRealisation' => RealisationResource::getUrl('create'),
            'profil' => ManageProfil::getUrl(),
            // Adresse publique du site (la première origine qui n'est pas locale)
            'site' => $origines->first(fn (string $o) => ! str_contains($o, 'localhost')) ?? $origines->first(),
            'cv' => CvPdf::where('actif', true)->latest()->first(),
            'gererCv' => CvPdfResource::getUrl('index'),
        ];
    }
}
