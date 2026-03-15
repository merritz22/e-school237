<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;

new class extends Component
{
    public User $user;
    public bool $class_filter_enabled = false;
    public bool $hasSubscription      = false;

    public function mount(): void
    {
        $info = $this->user->information;
        $this->class_filter_enabled = $info?->class_filter_enabled ?? false;
        $this->hasSubscription = $this->user
            ->subscriptions()
            ->where('status', 'active')
            ->exists();
    }

    #[On('save-profile')]
    public function save(): void
    {
        if (!$this->hasSubscription) return;

        $this->user->information()->updateOrCreate(
            ['user_id' => $this->user->id],
            ['class_filter_enabled' => $this->class_filter_enabled]
        );
    }
};
?>

<div>
    <flux:heading size="sm" class="mb-4 flex items-center gap-2">
        <flux:icon name="funnel" class="w-4 h-4" />
        {{ __('app.profile.sections.class_filter') }}
    </flux:heading>

    @if($hasSubscription)
        <flux:checkbox wire:model="class_filter_enabled"
                       label="{{ __('app.profile.fields.class_filter') }}" />
        <flux:text size="xs" class="text-zinc-400 mt-1">
            {{ __('app.profile.fields.class_filter_hint') }}
        </flux:text>
    @else
        <div class="flex items-center gap-3 p-3 rounded-lg
            bg-zinc-50 dark:bg-zinc-800
            border border-zinc-200 dark:border-zinc-700">
            <flux:icon name="lock-closed" class="w-4 h-4 text-zinc-400" />
            <flux:text size="sm" class="text-zinc-500">
                {{ __('app.profile.fields.class_filter_locked') }}
            </flux:text>
            <flux:button wire:navigate href="{{ route('subscriptions.index') }}"
                         size="xs" variant="primary" class="ml-auto">
                {{ __('app.profile.subscribe') }}
            </flux:button>
        </div>
    @endif
</div>