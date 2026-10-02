<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealisationResource\Pages;
use App\Models\Realisation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RealisationResource extends Resource
{
    protected static ?string $model = Realisation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Réalisations';

    protected static ?string $modelLabel = 'réalisation';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('titre')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true),

            Textarea::make('description')
                ->required()
                ->rows(4)
                ->columnSpanFull(),

                       FileUpload::make('image')
                ->label('Image')
                ->image()
                ->disk('public')
                ->directory('realisations')
                ->previewable(false)
                ->downloadable()
                ->columnSpanFull(),

            TextInput::make('lien_demo')
                ->label('Lien de la démo')
                ->url()
                ->maxLength(255),

            TextInput::make('lien_github')
                ->label('Lien du code source (GitHub)')
                ->url()
                ->maxLength(255),

            TagsInput::make('technologies')
                ->label('Technologies')
                ->placeholder('Appuie sur Entrée après chaque technologie')
                ->columnSpanFull(),

            DatePicker::make('date_realisation')
                ->label('Date de réalisation'),

            TextInput::make('ordre')
                ->numeric()
                ->default(0),

            Toggle::make('en_avant')
                ->label('Mettre en avant sur l\'accueil'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->disk('public'),
                TextColumn::make('titre')->searchable()->sortable(),
                TextColumn::make('date_realisation')->date('d/m/Y')->sortable(),
                IconColumn::make('en_avant')->boolean()->label('En avant'),
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
            'index' => Pages\ListRealisations::route('/'),
            'create' => Pages\CreateRealisation::route('/create'),
            'edit' => Pages\EditRealisation::route('/{record}/edit'),
        ];
    }
}
