<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Messages reçus';

    protected static ?string $modelLabel = 'message';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('lu', false)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nom')->label('Nom')->disabled(),
            TextInput::make('email')->label('Email')->disabled(),
            TextInput::make('sujet')->label('Sujet')->disabled(),
            Textarea::make('message')->label('Message')->disabled()->rows(6)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')->label('Nom')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('sujet')->label('Sujet')->limit(40),
                IconColumn::make('lu')->label('Lu')->boolean(),
                TextColumn::make('created_at')->label('Reçu le')->date('d/m/Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()
                    ->label('Infos')
                    ->icon('heroicon-o-information-circle'),
                Action::make('marquerLu')
                    ->label('Marquer comme lu')
                    ->icon('heroicon-o-check')
                    ->visible(fn (ContactMessage $record) => ! $record->lu)
                    ->action(fn (ContactMessage $record) => $record->update(['lu' => true])),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
        ];
    }
}
