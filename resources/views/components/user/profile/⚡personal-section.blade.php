<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;

new class extends Component
{
    public User $user;

    #[Validate('required|string|max:100', message: 'Le prénom est obligatoire.')]
    public string $first_name = '';

    #[Validate('required|string|max:100', message: 'Le nom est obligatoire.')]
    public string $last_name = '';

    #[Validate('nullable|string|max:500')]
    public string $bio = '';

    #[Validate('nullable|string|max:100')]
    public string $city = '';

    #[Validate('nullable|string|max:100')]
    public string $country = '';

    public function mount(): void
    {
        $this->first_name = $this->user->first_name ?? '';
        $this->last_name  = $this->user->last_name  ?? '';
        $this->bio        = $this->user->bio        ?? '';
        $this->city       = $this->user->city       ?? '';
        $this->country    = $this->user->country    ?? '';
    }

    #[On('save-profile')]
    public function save(): void
    {
        $this->validate();

        $this->user->update([
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'bio'        => $this->bio,
            'city'       => $this->city,
            'country'    => $this->country,
        ]);
    }
};
?>

<div>
    <div class="flex items-center gap-2 font-medium text-sm">
        <flux:icon name="user" class="w-4 h-4 text-zinc-400" />
        {{ __('app.profile.sections.personal') }}
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <flux:input wire:model="first_name"
                    label="{{ __('app.profile.fields.first_name') }}"
                    placeholder="Votre prénom" />
        <flux:error name="first_name" />

        <flux:input wire:model="last_name"
                    label="{{ __('app.profile.fields.last_name') }}"
                    placeholder="Votre nom" />
        <flux:error name="last_name" />

        <flux:input wire:model="city"
                    label="{{ __('app.profile.fields.city') }}"
                    placeholder="Ex: Yaoundé" icon="map-pin" />

        <flux:input wire:model="country"
                    label="{{ __('app.profile.fields.country') }}"
                    placeholder="Ex: Cameroun" icon="globe-alt" />
    </div>

    <div class="mt-4">
        <flux:textarea wire:model="bio"
                       label="{{ __('app.profile.fields.bio') }}"
                       placeholder="Parlez de vous en quelques mots..."
                       rows="3" />
    </div>
</div>