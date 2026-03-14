<?php

use Livewire\Component;
use App\Models\Article;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;

new class extends Component
{
    public $stats = [
        'total_articles' => 0,
        'total_subjects' => 0,
        'total_supports' => 0,
    ];

    public function mount()
    {
        $this->stats['total_articles'] = Article::count();
        $this->stats['total_subjects'] = EvaluationSubject::count();
        $this->stats['total_supports'] = EducationalResource::where('is_approved',1)->count();
    }
};
?>

{{-- ✅ Skeleton affiché pendant le chargement lazy --}}
<x-slot:placeholder>
    <div class="py-3">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @for($i = 0; $i < 3; $i++)
                    <flux:card class="p-5 space-y-3">
                        <flux:skeleton animate="shimmer" class="h-4 w-24 rounded" />
                        <div class="flex items-center gap-3">
                            <flux:skeleton animate="shimmer" class="h-8 w-8 rounded-lg" />
                            <flux:skeleton animate="shimmer" class="h-7 w-16 rounded" />
                        </div>
                        <flux:skeleton animate="shimmer" class="h-3 w-full rounded" />
                        <flux:skeleton animate="shimmer" class="h-3 w-2/3 rounded" />
                    </flux:card>
                @endfor
            </div>
        </div>
    </div>
</x-slot:placeholder>

{{-- ✅ Contenu réel --}}
<div class="py-3">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

            @php
                $theme = config('theme');
                $cards = [
                    [
                        'key'   => 'total_articles',
                        'icon'  => 'document-text',
                        'color' => $theme['primary'],
                        'label' => __('app.home.stats.articles'),
                        'desc'  => __('app.home.stats.articles_desc'),
                    ],
                    [
                        'key'   => 'total_subjects',
                        'icon'  => 'academic-cap',
                        'color' => $theme['success'],
                        'label' => __('app.home.stats.subjects'),
                        'desc'  => __('app.home.stats.subjects_desc'),
                    ],
                    [
                        'key'   => 'total_supports',
                        'icon'  => 'book-open',
                        'color' => $theme['warning'],
                        'label' => __('app.home.stats.supports'),
                        'desc'  => __('app.home.stats.supports_desc'),
                    ],
                ];
            @endphp

            @foreach($cards as $card)
                <flux:card class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <flux:heading size="sm">{{ $card['label'] }}</flux:heading>
                        <div class="p-2 rounded-lg bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/30">
                            <flux:icon
                                name="{{ $card['icon'] }}"
                                class="w-5 h-5 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400"
                            />
                        </div>
                    </div>

                    <span class="text-3xl font-bold text-zinc-800 dark:text-white">
                        {{ number_format($stats[$card['key']]) }}
                    </span>

                    <flux:text class="text-xs text-zinc-500">
                        {{ $card['desc'] }}
                    </flux:text>
                </flux:card>
            @endforeach

        </div>
    </div>
</div>