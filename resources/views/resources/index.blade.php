@extends('layouts.app')

@section('title', 'Supports pédagogiques')

@section('content')
<!-- Hero Section -->
@component('components.homepanel', 
    [
        'title' => 'Ressources Éducatives', 
        'description' => 'Trouvez des documents utiles pour votre formation.'])
@endcomponent
<div class="bg-white">
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        {{-- Fileter section --}}
        <div class="bg-gray-50 rounded-lg mb-3">
            <form action="{{ route('resources.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
                    <!-- Search -->
                    {{-- <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                        <input type="text" 
                                id="search" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Titre, contenu..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div> --}}
    
                    <!-- Subject Filter -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select id="subject" 
                                name="subject" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les matières</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->slug }}" 
                                        @if(request('subject') === $subject->slug) selected @endif>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Level Filter -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                        <select id="level" 
                                name="level" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les niveaux</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->slug }}" 
                                        @if(request('level') === $level->slug) selected @endif>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="bg-[#03386a] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                        
                        @if(request()->hasAny(['subject', 'level']))
                            <a href="{{ route('resources.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                                Effacer les filtres
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($resources as $resource)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-200">
                    @if($resource->preview_image)
                        <img src="{{ Storage::url($resource->preview_image) }}" 
                             alt="{{ $resource->title }}" 
                             class="w-full h-32 object-cover">
                    @else
                        <div class="w-full h-32 bg-purple-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded uppercase">
                                {{ $resource->level->name }}
                            </span>
                            @if($resource->category->name)
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded">{{ $resource->category->name }}</span>
                            @endif
                        </div>
                        
                        <div>
                            <h3 class="font-semibold mb-2 line-clamp-2">
                                <a href="{{ route('resources.show', $resource->id) }}" class="text-gray-900 hover:text-purple-600 flex">
                                    <svg class="w-4 h-4 mr-1 my-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg> {{ $resource->title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500 mb-2 text-wrap">{{ Str::limit($resource->description, 100) }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>{{ number_format($resource->file_size / 1024, 0) }} KB</span>
                            <span>{{ $resource->downloads_count }} téléchargements</span>
                        </div>
                    </div>
                </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">Aucun support trouvé</h3>
                <p class="mt-1 text-gray-500">Essayez de modifier vos critères de recherche.</p>
            </div>
            @endforelse
        </div>
    
        <div class="mt-8">
            {{ $resources->links() }}
        </div>
    </div>
</div>
@endsection