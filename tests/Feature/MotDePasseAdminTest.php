<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Auth\EditProfile;
use Filament\Pages\Auth\Login;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use Filament\Pages\Auth\PasswordReset\ResetPassword;
use Filament\Notifications\Auth\ResetPassword as EmailDeReinitialisation;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Tests\TestCase;

class MotDePasseAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_mot_de_passe_change_dans_le_profil_permet_de_se_reconnecter(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::create(['name' => 'Aissatou', 'email' => 'aissatou@example.com', 'password' => 'ancien-mot-de-passe']);

        // 1. Changement du mot de passe depuis la page Profil de l'admin
        $this->actingAs($admin);
        Livewire::test(EditProfile::class)
            ->fillForm(['password' => 'Nouveau-Mdp-2026', 'passwordConfirmation' => 'Nouveau-Mdp-2026'])
            ->call('save')
            ->assertHasNoFormErrors();

        // 2. Déconnexion, puis reconnexion avec le nouveau mot de passe
        Auth::logout();
        $this->assertTrue(Auth::validate(['email' => 'aissatou@example.com', 'password' => 'Nouveau-Mdp-2026']), 'Le nouveau mot de passe doit être accepté');
        $this->assertFalse(Auth::validate(['email' => 'aissatou@example.com', 'password' => 'ancien-mot-de-passe']), "L'ancien mot de passe doit être refusé");

        Livewire::test(Login::class)
            ->fillForm(['email' => 'aissatou@example.com', 'password' => 'Nouveau-Mdp-2026'])
            ->call('authenticate')
            ->assertHasNoFormErrors();
        $this->assertAuthenticatedAs($admin->fresh());
    }

    public function test_mot_de_passe_oublie_envoie_un_email_et_permet_d_en_choisir_un_nouveau(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Notification::fake();
        $admin = User::create(['name' => 'Aissatou', 'email' => 'aissatou@example.com', 'password' => 'mot-de-passe-oublie']);

        // La page de connexion propose le lien « Mot de passe oublié ? »
        $this->get('/admin/login')->assertOk()->assertSee('password-reset/request');

        // 1. Demande du lien : un email de réinitialisation est envoyé à l'admin
        Livewire::test(RequestPasswordReset::class)
            ->fillForm(['email' => 'aissatou@example.com'])
            ->call('request')
            ->assertHasNoFormErrors();
        Notification::assertSentTo($admin, EmailDeReinitialisation::class);

        // 2. Choix d'un nouveau mot de passe depuis le lien reçu
        $jeton = Password::broker('users')->createToken($admin);
        Livewire::test(ResetPassword::class, ['email' => 'aissatou@example.com', 'token' => $jeton])
            ->fillForm(['password' => 'Choisi-Par-Moi-2026', 'passwordConfirmation' => 'Choisi-Par-Moi-2026'])
            ->call('resetPassword')
            ->assertHasNoFormErrors();

        // 3. Reconnexion avec le nouveau mot de passe
        $this->assertTrue(Auth::validate(['email' => 'aissatou@example.com', 'password' => 'Choisi-Par-Moi-2026']));
    }
}
