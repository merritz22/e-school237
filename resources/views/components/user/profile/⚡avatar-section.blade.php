<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

new class extends Component
{
    use WithFileUploads;

    public User $user;
    public $newAvatar;

    public function updatedNewAvatar(): void
    {
        $this->uploadAvatar();
    }

    public function uploadAvatar(): void
    {
        $this->validateOnly('newAvatar', [
            'newAvatar' => 'image|max:2048',
        ]);

        if ($this->user->avatar_url) {
            Storage::disk('public')->delete($this->user->avatar_url);
        }

        $path = $this->newAvatar->store('avatars', 'public');
        $this->user->update(['avatar_url' => $path]);
        $this->newAvatar = null;
    }

    public function sendVerificationEmail(): void
    {
        Auth::user()->sendEmailVerificationNotification();
        $this->dispatch('verification-sent');
    }
};
?>

<div class="flex flex-col sm:flex-row items-center gap-6">

    @php $theme = config('theme'); @endphp

    {{-- Avatar --}}
    <div class="relative group cursor-pointer">
        <x-mary-file wire:model="newAvatar" accept="image/png, image/jpeg">
            @if($user->avatar_url)
                <img src="{{ asset('storage/' . $user->avatar_url) }}"
                     class="w-24 h-24 rounded-full object-cover
                        ring-4 ring-{{ $theme['primary'] }}-100" />
            @else
                <flux:avatar size="xl" name="{{ $user->name }}"
                             class="ring-4 ring-{{ $theme['primary'] }}-100" />
            @endif
            <div class="absolute inset-0 bg-black/40 rounded-full
                        opacity-0 group-hover:opacity-100
                        flex items-center justify-center transition-opacity">
                <flux:icon name="camera" class="w-6 h-6 text-white" />
            </div>
        </x-mary-file>
    </div>

    {{-- Identité --}}
    <div>
        <flux:heading size="xl">{{ $user->name }}</flux:heading>
        <flux:text class="text-zinc-500">{{ $user->email }}</flux:text>
        <div class="flex items-center gap-2 mt-2">
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                {{ $user->role === 'admin'     ? 'bg-red-100 text-red-700' :
                  ($user->role === 'moderator' ? 'bg-yellow-100 text-yellow-700' :
                  ($user->role === 'author'    ? 'bg-blue-100 text-blue-700' :
                                                 'bg-gray-100 text-gray-700')) }}">
                {{ ucfirst($user->role) }}
            </span>
            @if($user->is_active)
                <flux:badge color="green">Actif</flux:badge>
            @else
                <flux:badge color="red">Inactif</flux:badge>
            @endif
        </div>
    </div>

    {{-- Email vérifié --}}
    @if(!$user->email_verified_at)
        <div class="sm:ml-auto space-y-2">
            <flux:badge color="yellow" icon="exclamation-triangle">
                {{ __('app.profile.email_unverified') }}
            </flux:badge>
            <flux:button
                wire:click="sendVerificationEmail"
                size="sm"
                icon="envelope"
                class="w-full"
            >
                {{ __('app.profile.resend_verification') }}
            </flux:button>
        </div>
    @else
        <div class="sm:ml-auto">
            <flux:badge color="green" icon="check-circle">
                {{ __('app.profile.email_verified') }}
            </flux:badge>
        </div>
    @endif
</div>