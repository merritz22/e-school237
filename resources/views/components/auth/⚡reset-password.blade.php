<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

new class extends Component
{
    public $token;
    
    #[Validate('required', message: 'L\'adresse mail est un champs obligatoire')]
    #[Validate('email', message: 'Adresse email invalide')]
    #[Validate('exists:users,email', message: 'Cette adresse email n\'existe pas')]
    public $email;
    
    #[Validate('required', message: 'Le mot de passe est un champs obligatoire')]
    #[Validate('min:8', message: 'Le mot de passe doit contenir au moins 8 caractères')]
    public $password;

    #[Validate('required', message: 'Confirmation du mot de passe obligatoire')]
    #[Validate('same:password', message: 'Les mots de passe ne correspondent pas')]
    public $confirm_password;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->confirm_password,
                'token' => $this->token
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->redirectRoute('login');
        }

        $this->addError('credentials', 'Ce jeton de réinitialisation du mot de passe est invalide. Envoyez un nouveau mail de vérification.');
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
};
?>
