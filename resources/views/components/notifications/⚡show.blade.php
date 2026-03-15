<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $userNotifs = [];

    public function mount()
    {
        $this->userNotifs = Auth::user()
            ->notifications()
            ->wherePivot('is_visible', 1)
            ->wherePivot('read_at', null)
            ->latest()
            ->get();
    }

    public function markAsRead($notifId): void
    {
        Auth::user()
            ->notifications()
            ->updateExistingPivot($notifId, [
                'read_at' => now(),
                'is_visible' => 0
            ]); // ✅ read_at au lieu de is_read

        $this->userNotifs = Auth::user()
            ->notifications()
            ->wherePivot('is_visible', 1)
            ->wherePivot('read_at', null)
            ->latest()
            ->get();

        $this->dispatch('notif-read');// ✅ Émet un événement
    }
};
?>

{{-- ===== SKELETON ===== --}}
<x-slot:placeholder>
    <div class="space-y-3 p-2">
        <flux:skeleton animate="shimmer" class="h-6 w-48 rounded-full mb-5" />
        @for($i = 0; $i < 4; $i++)
            <div class="border-b border-zinc-200 dark:border-zinc-700 pb-3">
                <div class="flex justify-between items-center">
                    <flux:skeleton animate="shimmer" class="h-4 w-48 rounded" />
                    <flux:skeleton animate="shimmer" class="h-4 w-4 rounded" />
                </div>
            </div>
        @endfor
    </div>
</x-slot:placeholder>

{{-- ===== CONTENU ===== --}}
<div class="space-y-1">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        @if($userNotifs->isNotEmpty())
            <flux:text size="xs" class="text-zinc-400">
                {{$userNotifs->where('pivot.read_at', null)->count()}}
                {{ __('app.notifications.unread') }}
            </flux:text>
        @endif
    </div>

    {{-- Liste --}}
    @forelse($userNotifs as $notif)
        <div
            x-data="{ open: false }"
            wire:key="notif-{{ $notif->id }}"
            class="rounded-lg border transition-colors duration-200
                {{ $notif->pivot->read_at
                    ? 'border-zinc-200 dark:border-zinc-700'
                    : 'border-' . config('theme.primary') . '-200 bg-' . config('theme.primary') . '-50/50 dark:bg-' . config('theme.primary') . '-900/10' }}"
        >
            {{-- Trigger --}}
            <button
                x-on:click="open = !open; if (!open) $wire.markAsRead({{ $notif->id }})"
                class="w-full flex items-center justify-between gap-3 px-4 py-3 text-left"
            >
                <div class="flex items-center gap-2 min-w-0">
                    {{-- Point non lu --}}
                    @if(!$notif->pivot->read_at)
                        <span class="w-2 h-2 rounded-full bg-{{ config('theme.primary') }}-500 shrink-0"></span>
                    @else
                        <span class="w-2 h-2 shrink-0"></span>
                    @endif

                    <span class="text-sm font-medium truncate
                        {{ !$notif->pivot->is_read ? 'font-semibold' : '' }}">
                        {{ $notif->title }}
                    </span>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <flux:text size="xs" class="text-zinc-400 hidden sm:block">
                        {{ ($notif->created_at ?? $notif->pivot->created_at)?->diffForHumans() ?? '-' }}
                    </flux:text>
                    <flux:icon
                        x-show="!open"
                        name="chevron-down"
                        class="w-4 h-4 text-zinc-400"
                    />
                    <flux:icon
                        x-show="open"
                        name="chevron-up"
                        class="w-4 h-4 text-zinc-400"
                        x-cloak
                    />
                </div>
            </button>

            {{-- Contenu accordion --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1"
                class="px-4 pb-4 pt-0"
                x-cloak
            >
                <flux:separator class="mb-3" />
                <flux:text size="sm" class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                    {{ $notif->description }}
                </flux:text>
                <flux:text size="xs" class="text-zinc-400 mt-2 sm:hidden">
                    {{ ($notif->created_at ?? $notif->pivot->created_at)?->diffForHumans() ?? '-' }}
                </flux:text>
            </div>
        </div>
    @empty
        <div class="text-center py-10 space-y-3">
            <flux:icon name="bell-slash" class="w-10 h-10 text-zinc-300 mx-auto" />
            <flux:text class="text-zinc-500">
                {{ __('app.notifications.empty') }}
            </flux:text>
        </div>
    @endforelse

</div>