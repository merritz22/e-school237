<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public $success = false;
    public $user;
    public $newAvatar;

    // Infos de base
    public string $first_name = '';
    public string $last_name  = '';
    public string $bio        = '';
    public string $city       = '';
    public string $country    = '';

    // Contact
    public string $whatsapp = '';

    // Réseaux sociaux
    public string $facebook_url  = '';
    public string $tiktok_url    = '';
    public string $instagram_url = '';
    public string $twitter_url   = '';
    public string $youtube_url   = '';
    public string $linkedin_url  = '';
    public string $website_url   = '';

    public function mount($userId = null)
    {
        $this->user = $userId
            ? \App\Models\User::find($userId)
            : Auth::user();

        $this->first_name    = $this->user->first_name    ?? '';
        $this->last_name     = $this->user->last_name     ?? '';
        $this->bio           = $this->user->bio           ?? '';
        $this->city          = $this->user->city          ?? '';
        $this->country       = $this->user->country       ?? '';
        $this->whatsapp      = $this->user->whatsapp      ?? '';
        $this->facebook_url  = $this->user->facebook_url  ?? '';
        $this->tiktok_url    = $this->user->tiktok_url    ?? '';
        $this->instagram_url = $this->user->instagram_url ?? '';
        $this->twitter_url   = $this->user->twitter_url   ?? '';
        $this->youtube_url   = $this->user->youtube_url   ?? '';
        $this->linkedin_url  = $this->user->linkedin_url  ?? '';
        $this->website_url   = $this->user->website_url   ?? '';
    }

    public function updatedNewAvatar()
    {
        $this->uploadAvatar();
    }

    public function uploadAvatar()
    {
        $this->validate(['newAvatar' => 'image|max:2048']);

        if ($this->user->avatar_url) {
            Storage::disk('public')->delete($this->user->avatar_url);
        }

        $path = $this->newAvatar->store('avatars', 'public');
        $this->user->update(['avatar_url' => $path]);
        $this->newAvatar = null;

        $this->dispatch('avatar-updated');
        session()->flash('message', 'Avatar mis à jour !');
    }

    public function saveProfile()
    {
        $this->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'bio'           => 'nullable|string|max:500',
            'city'          => 'nullable|string|max:100',
            'country'       => 'nullable|string|max:100',
            'whatsapp'      => 'nullable|string|max:20',
            'facebook_url'  => 'nullable|url|max:255',
            'tiktok_url'    => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url'   => 'nullable|url|max:255',
            'youtube_url'   => 'nullable|url|max:255',
            'linkedin_url'  => 'nullable|url|max:255',
            'website_url'   => 'nullable|url|max:255',
        ]);

        $this->user->update([
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'bio'           => $this->bio,
            'city'          => $this->city,
            'country'       => $this->country,
            'whatsapp'      => $this->whatsapp,
            'facebook_url'  => $this->facebook_url,
            'tiktok_url'    => $this->tiktok_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url'   => $this->twitter_url,
            'youtube_url'   => $this->youtube_url,
            'linkedin_url'  => $this->linkedin_url,
            'website_url'   => $this->website_url,
        ]);

        $this->success = true;
    }

    public function sendVerificationEmail()
    {
        Auth::user()->sendEmailVerificationNotification();
        $this->success = true;
    }
};
?>

<div class="space-y-6">

    {{-- ===== CARTE PROFIL ===== --}}
    <flux:card class="p-6">

        {{-- Header avatar + nom --}}
        <div class="flex flex-col sm:flex-row items-center gap-6 mb-6">
            <div class="relative group cursor-pointer">
                <x-mary-file wire:model="newAvatar" accept="image/png, image/jpeg">
                    @if($user->avatar_url)
                        <img src="{{ asset('storage/' . $user->avatar_url) }}"
                             class="w-24 h-24 rounded-full object-cover ring-4 ring-blue-100" />
                    @else
                        <flux:avatar size="xl" name="{{ $user->name }}"
                                     class="ring-4 ring-blue-100" />
                    @endif
                    <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100
                                flex items-center justify-center transition-opacity">
                        <flux:icon name="camera" class="w-6 h-6 text-white" />
                    </div>
                </x-mary-file>
            </div>

            <div>
                <flux:heading size="xl">{{ $user->name }}</flux:heading>
                <flux:text class="text-gray-500">{{ $user->email }}</flux:text>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                        {{ $user->role === 'admin'     ? 'bg-red-100 text-red-700' :
                          ($user->role === 'moderator' ? 'bg-yellow-100 text-yellow-700' :
                          ($user->role === 'author'    ? 'bg-blue-100 text-blue-700' :
                                                         'bg-gray-100 text-gray-700')) }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->is_active)
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            Actif
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                            Inactif
                        </span>
                    @endif
                </div>
            </div>

            {{-- Vérification email --}}
            @if(!$user->email_verified_at)
                <div class="sm:ml-auto">
                    <flux:badge color="yellow" icon="exclamation-triangle">Email non vérifié</flux:badge>
                    <flux:button wire:click="sendVerificationEmail" size="sm" icon="envelope" class="mt-2">
                        Renvoyer la vérification
                    </flux:button>
                </div>
            @else
                <div class="sm:ml-auto">
                    <flux:badge color="green" icon="check-circle">Email vérifié</flux:badge>
                </div>
            @endif
        </div>

        <flux:separator />

        {{-- ===== FORMULAIRE ===== --}}
        <form wire:submit.prevent="saveProfile" class="mt-6 space-y-8">

            {{-- Section : Infos personnelles --}}
            <div>
                <flux:heading size="sm" class="mb-4 flex items-center gap-2">
                    <flux:icon name="user" class="w-4 h-4" /> Informations personnelles
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="first_name" label="Prénom" placeholder="Votre prénom" />
                    <flux:input wire:model="last_name"  label="Nom"    placeholder="Votre nom" />
                    <flux:input readonly variant="filled" label="Nom d'utilisateur" value="{{ $user->name }}" />
                    <flux:input readonly variant="filled" label="Email" value="{{ $user->email }}" />
                    <flux:input wire:model="city"    label="Ville"  placeholder="Ex: Yaoundé" icon="map-pin" />
                    <flux:input wire:model="country" label="Pays"   placeholder="Ex: Cameroun" icon="globe-alt" />
                </div>

                <div class="mt-4">
                    <flux:textarea wire:model="bio" label="Biographie"
                                   placeholder="Parlez de vous en quelques mots..."
                                   rows="3" />
                </div>
            </div>

            <flux:separator />

            {{-- Section : Contact --}}
            <div>
                <flux:heading size="sm" class="mb-4 flex items-center gap-2">
                    <flux:icon name="phone" class="w-4 h-4" /> Contact
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="whatsapp"
                                label="WhatsApp"
                                placeholder="+237 6XX XXX XXX"
                                icon="chat-bubble-left-ellipsis" />
                </div>
            </div>

            <flux:separator />

            {{-- Section : Réseaux sociaux --}}
            <div>
                <flux:heading size="sm" class="mb-4 flex items-center gap-2">
                    <flux:icon name="share" class="w-4 h-4" /> Réseaux sociaux
                </flux:heading>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <flux:input wire:model="facebook_url"
                                label="Facebook"
                                placeholder="https://facebook.com/votre-profil"
                                icon="link" />

                    <flux:input wire:model="instagram_url"
                                label="Instagram"
                                placeholder="https://instagram.com/votre-profil"
                                icon="link" />

                    <flux:input wire:model="tiktok_url"
                                label="TikTok"
                                placeholder="https://tiktok.com/@votre-profil"
                                icon="link" />

                    <flux:input wire:model="twitter_url"
                                label="X / Twitter"
                                placeholder="https://x.com/votre-profil"
                                icon="link" />

                    <flux:input wire:model="youtube_url"
                                label="YouTube"
                                placeholder="https://youtube.com/@votre-chaine"
                                icon="link" />

                    <flux:input wire:model="linkedin_url"
                                label="LinkedIn"
                                placeholder="https://linkedin.com/in/votre-profil"
                                icon="link" />

                    <flux:input wire:model="website_url"
                                label="Site web"
                                placeholder="https://votre-site.com"
                                icon="globe-alt" />
                </div>
            </div>

            {{-- Bouton sauvegarde --}}
            <div class="flex justify-end pt-2">
                <flux:button type="submit" variant="primary" icon="check">
                    Sauvegarder les modifications
                </flux:button>
            </div>

        </form>
    </flux:card>

    {{-- ===== MODAL SUCCÈS ===== --}}
    <flux:modal wire:model="success" :dismissible="true" size="sm" centered>
        <div class="text-center p-4 space-y-3">
            <flux:icon name="check-circle" class="w-12 h-12 text-green-500 mx-auto" />
            <flux:heading size="lg">Profil mis à jour !</flux:heading>
            <flux:text class="text-gray-500">Vos informations ont été sauvegardées avec succès.</flux:text>
            <flux:button wire:click="$set('success', false)" variant="primary" class="w-full">
                Fermer
            </flux:button>
        </div>
    </flux:modal>

</div>