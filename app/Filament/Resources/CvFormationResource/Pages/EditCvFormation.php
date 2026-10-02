<?php

namespace App\Filament\Resources\CvFormationResource\Pages;

use App\Filament\Resources\CvFormationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCvFormation extends EditRecord
{
    protected static string $resource = CvFormationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
