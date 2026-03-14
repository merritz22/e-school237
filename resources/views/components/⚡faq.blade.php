<?php

use Livewire\Component;

new class extends Component
{
    public array $faqs = [];
    public string $search = '';
    public string $jsonLd = '';

    public function mount(): void
    {
        $this->faqs = __('faq.items');

        $this->jsonLd = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => collect(__('faq.items'))->map(fn($faq) => [
                '@type' => 'Question',
                'name'  => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $faq['answer'],
                ],
            ])->values()->toArray(),
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function getFilteredFaqsProperty(): array
    {
        if (blank($this->search)) {
            return $this->faqs;
        }

        return collect($this->faqs)
            ->filter(fn($faq) =>
                str_contains(strtolower($faq['question']), strtolower($this->search)) ||
                str_contains(strtolower($faq['answer']), strtolower($this->search))
            )
            ->values()
            ->toArray();
    }
};
?>

<div class="min-h-screen bg-base-200/40 py-16 px-4">
<div class="max-w-3xl mx-auto">

    {{-- Hero Header --}}
    <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-base-content tracking-tight">
            {{ __('app.faq.title') }}
        </h1>
        <p class="text-base-content/50 mt-3 text-base max-w-xl mx-auto leading-relaxed">
            {{ __('app.faq.subtitle') }}
        </p>
    </div>

    {{-- Recherche --}}
    <div class="relative mb-8">
        <span class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-base-content/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </span>
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="{{ __('app.faq.search') }}"
            class="input input-bordered bg-base-100 w-full pl-11 pr-4 h-13 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-base"
        />
        @if($search)
            <button
                wire:click="$set('search', '')"
                class="absolute inset-y-0 right-4 flex items-center text-base-content/30 hover:text-base-content/70 transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif
    </div>

    {{-- Badge count --}}
    <div class="flex items-center gap-2 mb-5">
        <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary text-xs font-semibold px-3 py-1.5 rounded-full">
            <span class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span>
            {{ count($this->filteredFaqs) }} {{ __('app.faq.search_result') }}
        </span>
        @if($search)
            <span class="text-xs text-base-content/40">{{ __('pour') }} "<span class="italic">{{ $search }}</span>"</span>
        @endif
    </div>

    {{-- Accordion --}}
    <div class="space-y-2">
    @forelse($this->filteredFaqs as $index => $faq)
        <div
            wire:key="faq-{{ $index }}"
            x-data="{ open: false }"
            :class="open ? 'ring-2 ring-primary/20 shadow-md' : 'shadow-sm hover:shadow-md'"
            class="bg-base-100 rounded-2xl overflow-hidden transition-all duration-300"
        >
            {{-- Question --}}
            <button
                @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-5 text-left group"
            >
                <div class="flex items-center gap-4">
                    <span
                        :class="open ? 'bg-primary text-primary-content' : 'bg-base-200 text-base-content/50 group-hover:bg-primary/10 group-hover:text-primary'"
                        class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold transition-all duration-200"
                    >
                        {{ $index + 1 }}
                    </span>
                    <span
                        :class="open ? 'text-primary' : 'text-base-content'"
                        class="font-semibold text-sm leading-snug transition-colors duration-200"
                    >
                        {{ $faq['question'] }}
                    </span>
                </div>
                <span
                    :class="open ? 'bg-primary text-primary-content rotate-45' : 'bg-base-200 text-base-content/40 group-hover:bg-primary/10 group-hover:text-primary'"
                    class="flex-shrink-0 ml-4 w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
            </button>

            {{-- Réponse --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="px-6 pb-6"
            >
                <div class="ml-12 pl-0 border-l-2 border-primary/30 pl-4">
                    <p class="text-base-content/65 text-sm leading-relaxed">
                        {{ $faq['answer'] }}
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-20 bg-base-100 rounded-2xl">
            <div class="w-16 h-16 rounded-2xl bg-base-200 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="font-semibold text-base-content/60">{{ __('Aucun résultat trouvé') }}</p>
            <p class="text-xs text-base-content/35 mt-1">{{ __('Essayez avec d\'autres mots-clés') }}</p>
            <button wire:click="$set('search', '')" class="btn btn-sm btn-ghost mt-4 rounded-xl">
                {{ __('Réinitialiser la recherche') }}
            </button>
        </div>
    @endforelse
    </div>

    {{-- CTA Contact --}}
    <div class="mt-10 bg-base-100 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
        <div>
            <p class="font-semibold text-base-content text-sm">{{ __('Vous n\'avez pas trouvé votre réponse ?') }}</p>
            <p class="text-xs text-base-content/50 mt-0.5">{{ __('Notre équipe est disponible pour vous aider.') }}</p>
        </div>
        <a href="mailto:contact@e-school237.com" class="btn btn-primary btn-sm rounded-xl shrink-0 gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            {{ __('Nous contacter') }}
        </a>
    </div>

    {{-- JSON-LD SEO --}}
    @push('scripts')
        <script type="application/ld+json">{!! $jsonLd !!}</script>
    @endpush

</div>
</div>