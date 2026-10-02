<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CvFormationResource\Pages;
use App\Models\CvFormation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CvFormationResource extends Resource
{
    protected static ?string $model = CvFormation::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'CV - Formations';

    protected static ?string $modelLabel = 'formation';

    protected static ?string $navigationGroup = 'CV';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('diplome')->label('Diplôme')->required()->maxLength(255),
            TextInput::make('etablissement')->label('Établissement')->required()->maxLength(255),
            TextInput::make('lieu')->maxLength(255),
            DatePicker::make('date_debut')->required()->label('Date de début'),
            DatePicker::make('date_fin')->label('Date de fin'),
            Textarea::make('description')->rows(3)->columnSpanFull(),
            TextInput::make('ordre')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('diplome')->label('Diplôme')->searchable()->sortable(),
                TextColumn::make('etablissement')->label('Établissement')->searchable(),
                TextColumn::make('date_debut')->date('d/m/Y')->label('Début'),
                TextColumn::make('date_fin')->date('d/m/Y')->label('Fin'),
                TextColumn::make('ordre')->sortable(),
            ])
            ->defaultSort('ordre')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCvFormations::route('/'),
            'create' => Pages\CreateCvFormation::route('/create'),
            'edit' => Pages\EditCvFormation::route('/{record}/edit'),
        ];
    }
}
