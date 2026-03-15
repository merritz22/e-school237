<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $user;
    public bool $isComplete = false;
    public bool $saving     = false; // ✅ contrôle manuel du spinner
    public bool $success    = false;

    public function mount($userId = null): void
    {
        $this->user       = $userId ? User::find($userId) : Auth::user();
        $this->isComplete = $this->user->isProfileComplete();
    }

    // ✅ Action Livewire réelle — pas un simple dispatch JS
    public function saveAll(): void
    {
        $this->saving = true;
        $this->dispatch('save-profile');
    }

    #[On('profile-saved')]
    public function onProfileSaved(bool $isComplete): void
    {
        $this->isComplete = $isComplete;
        $this->saving     = false; // ✅ arrête le spinner
        $this->success    = true;
    }

    #[On('verification-sent')]
    public function onVerificationSent(): void {}
};
?>

<div class="space-y-6">
    @php $theme = config('theme'); @endphp

    {{-- Alerte profil incomplet --}}
    @if(!$isComplete)
        <div class="flex items-center gap-3 px-4 py-3 rounded-lg
            bg-yellow-50 dark:bg-yellow-900/20
            border border-yellow-200 dark:border-yellow-800">
            <flux:icon name="exclamation-triangle" class="w-5 h-5 text-yellow-600 shrink-0" />
            <flux:text size="sm" class="text-yellow-700 dark:text-yellow-400">
                {{ __('app.profile.incomplete_warning') }}
            </flux:text>
        </div>
    @endif

    <flux:card class="p-6 space-y-6">

        <livewire:user.profile.avatar-section :user="$user" />

        <flux:separator />

        <div class="space-y-10">
            <livewire:user.profile.personal-section    :user="$user" />
            <flux:separator />
            <livewire:user.profile.additional-section  :user="$user" />
            <flux:separator />
            <livewire:user.profile.contact-section     :user="$user" />
            <flux:separator />
            <livewire:user.profile.subjects-section    :user="$user" />
            <flux:separator />
            <livewire:user.profile.filter-section      :user="$user" />
            <flux:separator />
            <livewire:user.profile.social-section      :user="$user" />

            {{-- ✅ Bouton qui appelle saveAll() — vraie action Livewire --}}
            <div class="flex justify-end pt-2">
                <flux:button
                    wire:click="saveAll"
                    variant="primary"
                    icon="check"
                    wire:loading.attr="disabled"
                    wire:target="saveAll"
                >
                    <span wire:loading.remove wire:target="saveAll">
                        {{ __('app.profile.save') }}
                    </span>
                    <span wire:loading wire:target="saveAll" class="flex items-center gap-2">
                        <flux:icon name="arrow-path" class="w-4 h-4 animate-spin" />
                        {{ __('app.profile.saving') }}
                    </span>
                </flux:button>
            </div>
        </div>

    </flux:card>

    {{-- Modal succès --}}
    <flux:modal wire:model="success" :dismissible="true" size="sm" centered>
        <div class="text-center p-4 space-y-3">
            <flux:icon name="check-circle" class="w-12 h-12 text-green-500 mx-auto" />
            <flux:heading size="lg">{{ __('app.profile.success_title') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ __('app.profile.success_message') }}</flux:text>
            <flux:button wire:click="$set('success', false)" variant="primary" class="w-full">
                {{ __('app.common.close') }}
            </flux:button>
        </div>
    </flux:modal>

</div>