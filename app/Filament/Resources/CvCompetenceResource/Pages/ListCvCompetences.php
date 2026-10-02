<?php

namespace App\Filament\Resources\CvCompetenceResource\Pages;

use App\Filament\Resources\CvCompetenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCvCompetences extends ListRecords
{
    protected static string $resource = CvCompetenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
