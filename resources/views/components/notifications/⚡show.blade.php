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
            ->wherePivot('is_visible', true)
            ->latest()
            ->get();
    }
};
?>

<div>
    <flux:badge icon="bell" class="mb-5" size="lg">Liste des notifications</flux:badge>
    <!-- Accordion Item 1 -->
    @forelse  ( $userNotifs as $userNotif)
        <div class="border-b border-slate-200 pb-3">
            <button onclick="toggleAccordion({{ $userNotif->id }})" class="w-full flex justify-between items-center">
                <span>{{ $userNotif->title }}</span>
                <span id="icon-{{ $userNotif->id }}" class="transition-transform duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                        <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                    </svg>
                </span>
            </button>
            <div id="content-{{ $userNotif->id }}" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                <div class="pb-2 text-sm">
                    {{ $userNotif->description }}
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm">Aucune notification</p>
    @endforelse
    
    @script
    <script>
    window.toggleAccordion = function(index) {
        const content = document.getElementById(`content-${index}`);
        const icon = document.getElementById(`icon-${index}`);

        if (content.style.maxHeight && content.style.maxHeight !== '0px') {
            content.style.maxHeight = '0';
            icon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                </svg>
                `;
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                    <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
                </svg>
                `;
        }
    }
    </script>
    @endscript
</div>