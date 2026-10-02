<?php

namespace App\Filament\Resources\CvFormationResource\Pages;

use App\Filament\Resources\CvFormationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCvFormations extends ListRecords
{
    protected static string $resource = CvFormationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
