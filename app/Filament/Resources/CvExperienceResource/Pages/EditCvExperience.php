<?php

namespace App\Filament\Resources\CvExperienceResource\Pages;

use App\Filament\Resources\CvExperienceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCvExperience extends EditRecord
{
    protected static string $resource = CvExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
