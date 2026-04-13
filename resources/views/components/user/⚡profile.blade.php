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

        {{-- Avatar --}}
        <livewire:user.profile.avatar-section :user="$user" lazy>
            <x-slot:placeholder>
                <div class="flex flex-col sm:flex-row items-center gap-6 animate-pulse">
                    <div class="w-24 h-24 rounded-full bg-zinc-200 dark:bg-zinc-700 shrink-0"></div>
                    <div class="space-y-3 flex-1">
                        <div class="h-5 w-40 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="h-4 w-56 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="h-6 w-20 rounded-full bg-zinc-200 dark:bg-zinc-700"></div>
                    </div>
                </div>
            </x-slot:placeholder>
        </livewire:user.profile.avatar-section>

        <flux:separator />

        <div class="space-y-10">

            <livewire:user.profile.personal-section :user="$user" lazy>
                <x-slot:placeholder>
                    <div class="animate-pulse space-y-4">
                        <div class="h-4 w-36 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @for($i = 0; $i < 4; $i++)
                                <div class="space-y-1.5">
                                    <div class="h-3 w-24 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                                    <div class="h-10 w-full rounded-lg bg-zinc-200 dark:bg-zinc-700"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </x-slot:placeholder>
            </livewire:user.profile.personal-section>

            <flux:separator />

            <livewire:user.profile.additional-section :user="$user" lazy>
                <x-slot:placeholder>
                    <div class="animate-pulse space-y-4">
                        <div class="h-4 w-36 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @for($i = 0; $i < 6; $i++)
                                <div class="space-y-1.5">
                                    <div class="h-3 w-24 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                                    <div class="h-10 w-full rounded-lg bg-zinc-200 dark:bg-zinc-700"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </x-slot:placeholder>
            </livewire:user.profile.additional-section>

            <flux:separator />

            <livewire:user.profile.contact-section :user="$user" lazy>
                <x-slot:placeholder>
                    <div class="space-y-3 animate-pulse">
                        <div class="h-4 w-32 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="h-14 rounded-lg bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="h-14 rounded-lg bg-zinc-200 dark:bg-zinc-700"></div>
                    </div>
                </x-slot:placeholder>
            </livewire:user.profile.contact-section>

            <flux:separator />

            <livewire:user.profile.subjects-section :user="$user" lazy>
                <x-slot:placeholder>
                    <div class="animate-pulse space-y-4">
                        <div class="h-4 w-36 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @for($i = 0; $i < 8; $i++)
                                <div class="space-y-1.5">
                                    <div class="h-3 w-24 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                                    <div class="h-10 w-full rounded-lg bg-zinc-200 dark:bg-zinc-700"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </x-slot:placeholder>
            </livewire:user.profile.subjects-section>

            <flux:separator />

            <livewire:user.profile.social-section :user="$user" lazy>
                <x-slot:placeholder>
                    <div class="animate-pulse space-y-4">
                        <div class="h-4 w-36 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @for($i = 0; $i < 8; $i++)
                                <div class="space-y-1.5">
                                    <div class="h-3 w-24 rounded bg-zinc-200 dark:bg-zinc-700"></div>
                                    <div class="h-10 w-full rounded-lg bg-zinc-200 dark:bg-zinc-700"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </x-slot:placeholder>
            </livewire:user.profile.social-section>

            <div class="flex justify-end items-center gap-3 pt-2">
                <flux:button wire:click="saveAll" variant="primary" icon="check" size="sm">
                    {{ __('app.profile.save') }}
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