<?php

namespace App\Filament\Resources\CvCompetenceResource\Pages;

use App\Filament\Resources\CvCompetenceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCvCompetence extends EditRecord
{
    protected static string $resource = CvCompetenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
