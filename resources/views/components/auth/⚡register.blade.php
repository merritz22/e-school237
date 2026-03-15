<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserRegistered;

new class extends Component
{
    
    #[Validate('required', message: 'Le nom est un champs obligatoire')]
    #[Validate('min:3', message: 'Le nom doit contenir au moins 3 caractères')]
    public $name;
    
    #[Validate('required', message: 'Le prénom est un champs obligatoire')]
    #[Validate('min:3', message: 'Le prénom doit contenir au moins 3 caractères')]
    public $surname;
    
    #[Validate('required', message: 'L\'adresse mail est un champs obligatoire')]
    #[Validate('email', message: 'Adresse email invalide')]
    #[Validate('unique:users,email', message: 'Cette adresse email est déjà utilisée')]
    public $email;
    
    #[Validate('required', message: 'Veuillez acceptez les conditions')]
    public $terms;
    
    #[Validate('required', message: 'Le mot de passe est un champs obligatoire')]
    #[Validate('min:8', message: 'Le mot de passe doit contenir au moins 8 caractères')]
    public $password;

    #[Validate('required', message: 'Confirmation du mot de passe obligatoire')]
    #[Validate('same:password', message: 'Les mots de passe ne correspondent pas')]
    public $confirm_password;

    public function register()
    {
        $this->validate();

        $user = User::create([
            'first_name' => $this->name,
            'last_name' => $this->surname,
            'name' => $this->name . ' ' . $this->surname,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'member',
            'is_active' => true,
        ]);

        // event(new Registered($user)); // ← déclenche le listener
        Mail::to('admin@e-school237.com')->send(new NewUserRegistered($user));

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return $this->redirectRoute('user.profile');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
};
?>

