<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CvCompetenceResource\Pages;
use App\Models\CvCompetence;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CvCompetenceResource extends Resource
{
    protected static ?string $model = CvCompetence::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'CV - Compétences';

    protected static ?string $modelLabel = 'compétence';

    protected static ?string $navigationGroup = 'CV';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nom')->label('Nom')->required()->maxLength(255),
            Select::make('categorie')
                ->label('Catégorie')
                ->options([
                    'backend' => 'Backend',
                    'frontend' => 'Frontend',
                    'outils' => 'Outils',
                    'autre' => 'Autre',
                ])
                ->default('autre')
                ->required(),
            TextInput::make('niveau')
                ->label('Niveau (0-100)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100),
            TextInput::make('ordre')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')->label('Nom')->searchable()->sortable(),
                TextColumn::make('categorie')->label('Catégorie')->badge(),
                TextColumn::make('niveau')->label('Niveau'),
                TextColumn::make('ordre')->sortable(),
            ])
            ->defaultSort('ordre')
            ->actions([
                ViewAction::make()
                    ->label('Infos')
                    ->icon('heroicon-m-eye')
                    ->color('success'),
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
            'index' => Pages\ListCvCompetences::route('/'),
            'create' => Pages\CreateCvCompetence::route('/create'),
            'edit' => Pages\EditCvCompetence::route('/{record}/edit'),
        ];
    }
}
