<?php

namespace App\Filament\Resources\CvPdfResource\Pages;

use App\Filament\Resources\CvPdfResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCvPdfs extends ListRecords
{
    protected static string $resource = CvPdfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
