
<div>
    <!-- Filters -->
    <div class="rounded-lg mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Matière -->
            <flux:select wire:model.live="subject_id" label="Matière">
                <option value="">Toutes les matières</option>
                @foreach($filter_subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </flux:select>

            <!-- Niveau -->
            <flux:select wire:model.live="level_id" label="Niveau">
                <option value="">Tous les niveaux</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            </flux:select>

            <!-- Type -->
            <flux:select wire:model.live="type" label="Type">
                <option value="">Tous les types</option>
                @foreach($types as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </flux:select>

            <!-- Année -->
            <flux:select wire:model.live="year" label="Année">
                <option value="">Choisir une année</option>
                @foreach($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </flux:select>
        </div>
        {{-- Recherche --}}
        <flux:input label="Recherche par nom"
            wire:model.live.debounce.300ms="search"
            placeholder="Rechercher un sujet..."
            icon="magnifying-glass"
           
        />
    </div>

    <!-- Subjects list -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($subjects as $subject)
            <flux:card >
                <div class="w-full h-40 overflow-hidden rounded-md bg-gray-100">
                    <img
                        src="{{ Storage::url($subject->preview_image) }}"
                        alt="Aperçu de {{ $subject->title }}"
                        class="w-full h-full object-cover object-top"
                    />
                </div>

                <flux:heading size="md" class="mt-1">
                    <a href="{{ route('subjects.show', $subject->id) }}">
                        {{ $subject->title }}
                    </a>
                </flux:heading>

                <flux:badge icon="sparkles" variant="solid" color="{{ $subject->is_free ? 'emerald' : 'yellow' }}">
                    {{ $subject->is_free ? 'Gratuit' : 'Premium' }}
                </flux:badge>

                <div class="flex items-center justify-between mt-2">
                    @if($subject->type)
                        <flux:badge>
                            {{ $subject->type }}
                        </flux:badge>
                    @endif
                    @if($subject->level->name)
                        <flux:badge>
                            {{ $subject->level->name }}
                        </flux:badge>
                    @endif
                    @if($subject->subject->name)
                        <flux:badge color="blue">
                            {{ $subject->subject->name }}
                        </flux:badge>
                    @endif
                </div>

                <flux:text class="mt-1">
                    {{ Str::limit($subject->description, 140) }}
                </flux:text>

                <div class="w-full">
                    <div class="flex items-center justify-between text-sm">
                        <span>{{ number_format($subject->file_size / 1024, 0) }} KB</span>
                        <span>{{ $subject->downloads_count }} téléchargements</span>
                    </div>
                </div>
            </flux:card>
        @empty
            <div class="col-span-3 text-center py-12">
                <p>Aucun sujet trouvé.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($subjects->hasPages())
        <div class="mt-8">
            {{ $subjects->links() }}
        </div>
    @endif
</div>