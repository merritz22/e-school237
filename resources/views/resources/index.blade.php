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
        {{-- <div class="bg-gray-50 rounded-lg mb-3">
            <form action="{{ route('resources.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-5">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                        <input type="text" 
                                id="search" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Titre, contenu..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
    
                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select id="category" 
                                name="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les matières</option>
                            @foreach($subjects as $category)
                                <option value="{{ $category->slug }}" 
                                        @if(request('category') === $category->slug) selected @endif>
                                    {{ $category->name }} ({{ $category->resources_count }})
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
                                    {{ $level->name }} ({{ $level->resources_count }})
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
                        
                        @if(request()->hasAny(['search', 'category', 'sort']))
                            <a href="{{ route('articles.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                                Effacer les filtres
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
     --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($resources as $resource)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                <a href="{{ route('resources.show', $resource) }}" class="hover:text-blue-600">{{ $resource->title }}</a>
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit($resource->description, 100) }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ strtoupper($resource->file_extension) }}
                        </span>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="text-sm text-gray-500">{{ $resource->formatted_file_size }}</span>
                        <span class="text-sm text-gray-500">{{ $resource->downloads_count }} téléchargements</span>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                    <span class="text-sm text-gray-600">{{ $resource->category->name }}</span>
                    <a href="{{ route('resources.download', $resource) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Télécharger
                    </a>
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