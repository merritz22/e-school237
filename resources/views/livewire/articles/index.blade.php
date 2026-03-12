
<div>
    <div class="grid md:grid-cols-3 gap-4 mb-8 mt-3">
        {{-- filtre matière --}}
        <flux:select wire:model.live="subject" placeholder="Toutes les matières">
            <option value="">Toutes les matières</option>
            @foreach($subjects as $sub)
                <option value="{{ $sub->slug }}">
                    {{ $sub->name }}
                </option>
            @endforeach
        </flux:select>
        {{-- tri --}}
        <flux:select wire:model.live="sort">
            <option value="latest">Plus récents</option>
            <option value="popular">Plus populaires</option>
            <option value="oldest">Plus anciens</option>
            <option value="title">Titre A → Z</option>
        </flux:select>
        {{-- tri sur le titre --}}
        <flux:input
            wire:model.live.debounce.300ms="search"
            placeholder="Rechercher un article..."
            icon="magnifying-glass"
        />
    </div>
    {{-- liste articles --}}

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
            <flux:card class="cursor-pointer">
                    @if($article->featured_image)
                        <img
                            src="{{ Storage::url($article->featured_image) }}"
                            class="w-full h-40 object-cover rounded-t-lg"
                        >
                    @endif
                    <div class="p-5">
                        <div class="flex justify-between mb-2 text-sm">
                            <flux:badge>
                                {{ $article->subject->name }}
                            </flux:badge>
                            <span>
                                {{ $article->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <flux:heading size="xl" class="mb-2">
                            <a
                                wire:navigate
                                href="{{ route('articles.show',$article->slug) }}"
                            >
                                {{ $article->title }}
                            </a>
                        </flux:heading>
                        @if($article->excerpt)
                            <p class="text-sm line-clamp-3 mb-3">
                                {{ $article->excerpt }}
                            </p>
                        @endif
                        <div class="flex justify-between text-sm">
                            <span>{{ $article->author->name }}</span>
                            <span>{{ number_format($article->views_count) }} vues</span>
                        </div>
                </div>
            </flux:card>
        @empty
            <div class="col-span-3 text-center py-20">
                Aucun article disponible
            </div>
        @endforelse
    </div>
    {{-- pagination --}}
    <div class="mt-10">
        {{ $articles->links() }}
    </div>
</div>
