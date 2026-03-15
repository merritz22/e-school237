<?php

use Livewire\Component;
use App\Models\User;
use App\Http\Middleware\TrackUserPresence;

new class extends Component
{
    public int $onlineCount = 0;
    public int $totalCount  = 0;

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->onlineCount = TrackUserPresence::getOnlineCount();
        $this->totalCount  = User::count();
    }
};
?>

{{-- ✅ wire:poll sur la div racine --}}
<div wire:poll.30000ms="loadStats"
    class="flex items-center gap-3 px-3 py-1.5 rounded-xl
    border border-zinc-200 dark:border-zinc-700
    backdrop-blur-sm">

    <div class="flex items-center gap-2">

        {{-- En ligne --}}
        <div class="flex items-center gap-1">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full
                    rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-green-500"></span>
            </span>
            <span class="text-xs font-bold text-green-600 dark:text-green-400">
                {{ number_format($onlineCount) }}
            </span>
        </div>

        <flux:icon name="users" class="w-5 h-5 text-zinc-400 dark:text-zinc-500 shrink-0" />

        {{-- Total --}}
        <div class="flex items-center gap-1">
            <span class="relative inline-flex h-2 w-2 rounded-full bg-red-500"></span>
            <span class="text-xs font-medium text-red-400 dark:text-red-500">
                {{ number_format($totalCount) }}
            </span>
        </div>
    </div>
</div>