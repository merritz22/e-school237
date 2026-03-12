<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

new class extends Component
{
    #[Validate('required', message: 'L\'adresse mail est un champs obligatoire')]
    public $email;

    
    #[Validate('required', message: 'Le mot de passe est un champs obligatoire')]
    #[Validate('min:8', message: 'Le mot de passe doit contenir au moins 8 caractères')]
    public $password;

    public function login()
    {
        $this->validate();

        if(Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ]))
        {
            if(!Auth::user()->email_verified_at)
            {
                // Création de la notification
                NotificationService::send(
                    'VERIFY_EMAIL',
                    Auth::user(),
                    []
                );
            }
            return $this->redirectRoute('home', navigate: true);
        }

        $this->reset('password');

        $this->addError('credentials', 'Email ou mot de passe incorrect.');

    }

    public function render()
    {
        return view('livewire.auth.login');
    }
};
?>
