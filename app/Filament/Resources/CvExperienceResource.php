<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CvExperienceResource\Pages;
use App\Models\CvExperience;
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

class CvExperienceResource extends Resource
{
    protected static ?string $model = CvExperience::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'CV - Expériences';

    protected static ?string $modelLabel = 'expérience';

    protected static ?string $navigationGroup = 'CV';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('poste')->required()->maxLength(255),
            TextInput::make('entreprise')->required()->maxLength(255),
            TextInput::make('lieu')->maxLength(255),
            DatePicker::make('date_debut')->required()->label('Date de début'),
            DatePicker::make('date_fin')->label('Date de fin')->helperText('Laisser vide si poste actuel'),
            Textarea::make('description')->rows(3)->columnSpanFull(),
            TextInput::make('ordre')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('poste')->searchable()->sortable(),
                TextColumn::make('entreprise')->searchable(),
                TextColumn::make('date_debut')->date('d/m/Y')->label('Début'),
                TextColumn::make('date_fin')->date('d/m/Y')->label('Fin')->placeholder('En cours'),
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
            'index' => Pages\ListCvExperiences::route('/'),
            'create' => Pages\CreateCvExperience::route('/create'),
            'edit' => Pages\EditCvExperience::route('/{record}/edit'),
        ];
    }
}
