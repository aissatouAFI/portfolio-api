<?php

namespace App\Filament\Pages;

use App\Models\Profil;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageProfil extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profil (accueil)';

    protected static ?string $title = 'Profil public';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.manage-profil';

    public ?array $data = [];

    public function mount(): void
    {
        $profil = Profil::first() ?? new Profil();

        $this->form->fill([
            'photo' => $profil->photo,
            'titre_accroche' => $profil->titre_accroche,
            'sous_titre' => $profil->sous_titre,
            'a_propos' => $profil->a_propos,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photo')
                    ->label('Photo de profil')
                    ->image()
                    ->disk('public')
                    ->directory('profil')
                    ->previewable(false)
                    ->downloadable(),

                TextInput::make('titre_accroche')
                    ->label('Titre d\'accroche (hero)')
                    ->maxLength(255),

                Textarea::make('sous_titre')
                    ->label('Sous-titre (hero)')
                    ->rows(3),

                Textarea::make('a_propos')
                    ->label('À propos')
                    ->rows(6),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $profil = Profil::first() ?? new Profil();
        $profil->fill($data);
        $profil->save();

        Notification::make()
            ->title('Profil mis à jour avec succès.')
            ->success()
            ->send();
    }
}
