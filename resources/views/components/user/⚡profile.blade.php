<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; 

new class extends Component
{
    use WithFileUploads; 

    public $success = false;
    public $user;
    public $newAvatar;

    public function mount($userId = null)
    {
        // si pas d'ID fourni, prend l'utilisateur connecté
        $this->user = $userId ? \App\Models\User::find($userId) : Auth::user();
    }

    // Lancer automatiquement la sauvegarde lorsque le fichier change
    public function updatedNewAvatar()
    {
        $this->uploadAvatar();
    }

    public function sendVerificationEmail()
    {
        Auth::user()->sendEmailVerificationNotification();

        $this->success = true;
    }


    // méthode pour uploader l'image
    public function uploadAvatar()
    {
        $this->validate([
            'newAvatar' => 'image|max:2048', // max 2MB
        ]);

        // Supprime l'ancien avatar si existant
        if ($this->user->avatar_url) {
            Storage::disk('public')->delete($this->user->avatar_url);
        }

        // Stocke le nouveau
        $path = $this->newAvatar->store('avatars', 'public');
        $this->user->avatar_url = $path;
        $this->user->save();

        $this->newAvatar = null;
        session()->flash('message', 'Avatar mis à jour avec succès !');
    }
};
?>

<div>
    <flux:card>

        <!-- Header -->
        <div class="py-3 flex items-center gap-4">
            @if($user->avatar_url)
                <x-mary-file wire:model="newAvatar" accept="image/png, image/jpeg">
                    <img src="{{ asset('storage/'.$user->avatar_url) }}" class="w-25 h-25 rounded-lg" />
                </x-mary-file>
            @else
                <x-mary-file wire:model="newAvatar" accept="image/png, image/jpeg">
                    <flux:avatar size="lg" name="{{ $user->name }}" />
                </x-mary-file>
            @endif

            <!-- Titre -->
            <flux:heading size="xl">Informations générales</flux:heading>
        </div>

        <flux:separator />

        <!-- Contenu -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
            <flux:input readonly variant="filled" label="Nom d'utilisateur" value="{{ $user->name }}"/>
            <flux:input readonly variant="filled" label="Prénom" value="{{ $user->first_name }}"/>
            <flux:input readonly variant="filled" label="Nom" value="{{ $user->last_name }}"/>
            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input readonly variant="filled" value="{{ $user->email }}" />
            </flux:field>
            <div class="block">
                <flux:text>Rôle</flux:text>
                <span class="inline-block px-3 py-1 text-sm font-medium rounded
                    {{ $user->role === 'admin' ? 'bg-red-600 text-white' : 
                    ($user->role === 'moderator' ? 'bg-yellow-400 text-black' : 
                    ($user->role === 'author' ? 'bg-blue-400 text-white' : 'bg-gray-400 text-white')) }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <div class="block">
                <flux:text>Statut</flux:text>
                <span>
                    @if($user->is_active)
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-green-600 text-white">Actif</span>
                    @else
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-red-600 text-white">Inactif</span>
                    @endif
                </span>
            </div>
            @if(!$user->email_verified_at)
                <form wire:submit.prevent="sendVerificationEmail">
                    @csrf

                    <flux:button type="submit" icon="envelope" variant="primary">
                        Recevoir un nouvel email de vérification
                    </flux:button>
                </form>
            @endif
        </div>
    </flux:card>

    <flux:modal :dismissible="true" wire:model="success" size="md" centered>
        <flux:icon name="check-circle"/>
        <flux:heading size="lg">Un mail à été envoyé à votre adresse mail</flux:heading>    
    </flux:modal>
</div>