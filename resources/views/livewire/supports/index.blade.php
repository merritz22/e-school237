<div>
    {{-- Section filtres --}}
    <div class="grid md:grid-cols-2 gap-4 mb-8 mt-3 items-end">
        {{-- Matière --}}
        <flux:select wire:model.live="subject_id" placeholder="Toutes les matières" label="Matière">
            <option value="">Toutes les matières</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->slug }}">
                    {{ $subject->name }}
                </option>
            @endforeach
        </flux:select>

        {{-- Niveau --}}
        <flux:select wire:model.live="level_id" placeholder="Tous les niveaux" label="Niveau">
            <option value="">Tous les niveaux</option>
            @foreach($levels as $level)
                <option value="{{ $level->slug }}">
                    {{ $level->name }}
                </option>
            @endforeach
        </flux:select>

        {{-- Recherche --}}
        <flux:input label="Recherche par nom"
            wire:model.live.debounce.300ms="search"
            placeholder="Rechercher un support..."
            icon="magnifying-glass"
           
        />
    </div>
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        {{-- Liste des ressources --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($resources as $resource)
                <flux:card >
                    <div class="w-full h-40 overflow-hidden rounded-md bg-gray-100">
                        @if($resource->preview_image)
                            <img
                                src="{{ asset('storage/' . $resource->preview_image) }}"
                                alt="Aperçu de {{ $resource->title }}"
                                class="w-full h-full object-cover object-top"
                            />
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <flux:icon name="book-open" class="w-12 h-12 text-gray-400"/>
                            </div>
                        @endif
                    </div>
                    <flux:heading size="md" class="font-semibold mb-2 line-clamp-2">
                        <a href="{{ route('resources.show', $resource->id) }}">
                            {{ $resource->title }}
                        </a>
                    </flux:heading>

                    <flux:badge icon="sparkles" variant="solid" color="{{ $resource->is_free ? 'emerald' : 'yellow' }}">
                        {{ $resource->is_free ? 'Gratuit' : 'Premium' }}
                    </flux:badge>

                    <div class="flex items-center justify-between mt-2">
                        <flux:badge>
                            {{ $resource->level->name }}
                        </flux:badge>
                        @if($resource->subject->name)
                            <flux:badge color="blue">
                                {{ $resource->subject->name }}
                            </flux:badge>
                        @endif
                    </div>
                    <div class="w-full">
                        <div class="flex items-center justify-between text-sm">
                            <span>{{ number_format($resource->file_size / 1024, 0) }} KB</span>
                            <span>{{ $resource->downloads_count }} téléchargements</span>
                        </div>
                    </div>
                </flux:card>
            @empty
                <div class="col-span-4 text-center py-12">
                    <flux:text class="text-gray-500">Aucun support disponible.</flux:text>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $resources->links() }}
        </div>
    </div>
</div>