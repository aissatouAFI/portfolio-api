<?php

namespace App\Filament\Resources\CvExperienceResource\Pages;

use App\Filament\Resources\CvExperienceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCvExperiences extends ListRecords
{
    protected static string $resource = CvExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
