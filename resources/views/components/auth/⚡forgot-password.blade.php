<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;

new class extends Component
{
    #[Validate('required', message: 'L\'adresse mail est un champs obligatoire')]
    #[Validate('email', message: 'Adresse email invalide')]
    public $email;

    public $statusMessage = null;
    public $statusType = null;

    public function send()
    {
        $this->validate();

        $status = Password::sendResetLink([
            'email' => $this->email
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->statusMessage = 'Email envoyé ! Vérifiez votre boîte de réception.';
            $this->statusType = 'success';
        } else {
            $this->statusMessage = 'Impossible d’envoyer l’email.';
            $this->statusType = 'error';
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
};

?>