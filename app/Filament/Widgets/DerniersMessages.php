<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DerniersMessages extends BaseWidget
{
    protected static ?string $heading = 'Derniers messages reçus';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ContactMessage::query()->latest()->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('nom')->label('Nom'),
                TextColumn::make('sujet')->label('Sujet')->limit(40)->placeholder('Sans sujet'),
                IconColumn::make('lu')->label('Lu')->boolean(),
                TextColumn::make('created_at')->label('Reçu le')->dateTime('d/m/Y à H:i'),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Infos')
                    ->icon('heroicon-m-eye')
                    ->color('success')
                    ->modalHeading(fn (ContactMessage $record) => $record->sujet ?: 'Message de ' . $record->nom)
                    ->infolist([
                        TextEntry::make('nom')->label('Nom'),
                        TextEntry::make('email')->label('Email')->copyable(),
                        TextEntry::make('created_at')->label('Reçu le')->dateTime('d/m/Y à H:i'),
                        TextEntry::make('message')->label('Message'),
                    ]),
                Action::make('marquerLu')
                    ->label('Marquer comme lu')
                    ->icon('heroicon-m-check')
                    ->visible(fn (ContactMessage $record) => ! $record->lu)
                    ->action(fn (ContactMessage $record) => $record->update(['lu' => true])),
            ])
            ->emptyStateHeading('Aucun message pour l\'instant')
            ->emptyStateDescription('Les messages envoyés depuis ton formulaire de contact apparaîtront ici.');
    }
}
