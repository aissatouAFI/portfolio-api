<?php

namespace App\Filament\Resources\CvPdfResource\Pages;

use App\Filament\Resources\CvPdfResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCvPdf extends EditRecord
{
    protected static string $resource = CvPdfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
