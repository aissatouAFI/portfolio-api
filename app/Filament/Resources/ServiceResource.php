<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Ce que je fais';

    protected static ?string $modelLabel = 'service';

    protected static ?string $pluralModelLabel = 'Ce que je fais';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('titre')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->required()
                ->rows(4)
                ->columnSpanFull(),
            TextInput::make('icone')
                ->label('Icône (nom)')
                ->helperText('Ex : code, server, design')
                ->maxLength(255),
            TextInput::make('ordre')
                ->numeric()
                ->default(0),
            Toggle::make('actif')
                ->label('Actif (visible sur le site)')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titre')->searchable()->sortable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('ordre')->sortable(),
                IconColumn::make('actif')->boolean(),
            ])
            ->defaultSort('ordre')
            ->filters([])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make()
                    ->label('Infos')
                    ->icon('heroicon-m-eye')
                    ->color('success'),
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
