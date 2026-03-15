<x-layouts.app>
    @php $theme = config('theme'); @endphp
    {{-- ===== HERO ===== --}}
    <div class="text-center max-w-2xl mx-auto space-y-4">
        <flux:badge color="{{ $theme['warning'] }}" class="mb-2">
            🎓 {{ __('app.subscriptions.hero.badge') }}
        </flux:badge>
    </div>
    <livewire:subscriptions.store lazy />
</x-layouts.app>