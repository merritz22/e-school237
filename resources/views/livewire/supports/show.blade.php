<div class="container mx-auto py-4">
    <flux:card class=" rounded-lg shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="border-b flex justify-between items-start">
            <div>
                <flux:heading size="xl">{{ $resource->title }}</flux:heading>
                <div class="flex items-center mt-2 text-sm">
                    <span class="mr-4">Posté par {{ $resource->uploader->name }}</span>
                    <span>{{ $resource->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            <flux:badge icon="sparkles" color="{{ $resource->is_free ? 'green' : 'yellow' }}" class="flex items-center gap-1 text-sm font-semibold">
                {{ $resource->is_free ? 'Gratuit' : 'Premium' }}
            </flux:badge>
        </div>

        {{-- Meta info --}}
        <div class="flex items-center justify-between mt-2 text-sm">
            <flux:badge>
               Niveau : {{ $resource->level?->name }}
            </flux:badge>
            <flux:badge color="blue">
               Matière : {{ $resource->subject?->name ?? 'N/A' }}
            </flux:badge>
        </div>

        {{-- Description --}}
        <div>
            <flux:text class="mt-2">
                {{ $resource->description }}
            </flux:text>

            {{-- Fichier & téléchargement --}}
            <div class="rounded-lg flex items-center gap-4 mt-2">
                @if($resource->isImage())
                    <img class="h-12 w-12 object-cover rounded" src="{{ $resource->getFileUrl() }}" alt="">
                @else
                    <div class="h-12 w-12 rounded flex items-center justify-center">
                        <span class=" font-bold">{{ strtoupper($resource->file_extension) }}</span>
                    </div>
                @endif

                <div>
                    <div class="text-sm font-medium">{{ $resource->file_name }}</div>
                    <div class="text-sm">{{ $resource->formatted_file_size }}</div>
                </div>

                <div class="ml-auto">
                    <flux:button
                        icon="arrow-down-on-square"
                        href="{{ route('resources.download', $resource) }}" 
                        color="blue">
                        Télécharger
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- Ressources similaires --}}
        <div class="py-4 border-t">
            <flux:heading size="lg" class="mb-4">Ressources similaires</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relatedResources as $related)
                    <flux:card class="hover:shadow-md transition-shadow p-4">
                        <a href="{{ route('resources.show', $related) }}" class="block">
                            <flux:heading size="md">{{ $related->title }}</flux:heading>
                            <div class="mt-2 flex items-center text-sm">
                                <span>{{ $related->subject->name ?? '' }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $related->downloads_count }} téléchargements</span>
                            </div>
                        </a>
                    </flux:card>
                @endforeach
            </div>
        </div>
    </flux:card>
</div>