<?php

use Livewire\Component;
use App\Models\Article;

new class extends Component
{
    public $latest_articles = [];

    public function mount()
    {
        $this->latest_articles = Article::with(['subject','author'])
            ->where('status','published')
            ->latest()
            ->take(6)
            ->get();
    }
};
?>

<div class="mb-12">
    <div class="flex justify-between items-center mb-8">
        <flux:heading size="2xl" class="font-bold text-gray-900">Derniers Articles</flux:heading>
        <flux:button href="{{ route('articles.index') }}" icon:trailing="arrow-right">
            Voir tous les articles
        </flux:button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($latest_articles as $article)
            <flux:card >
                @if($article->featured_image)
                    <img src="{{ Storage::url($article->featured_image) }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-48 object-cover">
                @endif

                <div class="p-6">
                    <div class="flex items-center mb-3">
                        <flux:badge color="blue">
                            {{ $article->subject->name }}
                        </flux:badge>
                        <span class="text-gray-500 text-sm ml-auto">
                            {{ $article->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <flux:heading size="lg" class="font-semibold mb-2 line-clamp-2">
                        <a href="{{ route('articles.show', $article->slug) }}" >
                            {{ $article->title }}
                        </a>
                    </flux:heading>

                    @if($article->excerpt)
                        <flux:text size="sm">
                            {{ $article->excerpt }}
                        </flux:text>
                    @endif

                    <div class="flex items-center justify-between text-sm text-gray-500 mt-2">
                        <div class="flex items-center">
                            <img src="{{ $article->author->avatar ? Storage::url($article->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) . '&background=3b82f6&color=fff' }}" 
                                 alt="{{ $article->author->name }}" 
                                 class="w-6 h-6 rounded-full mr-2">
                            {{ $article->author->name }}
                        </div>
                        <span>{{ number_format($article->views_count) }} vues</span>
                    </div>
                </div>
            </flux:card>
        @empty
            <div class="col-span-3 text-center py-12">
                <flux:text class="text-gray-500">Aucun article disponible.</flux:text>
            </div>
        @endforelse
    </div>
</div>