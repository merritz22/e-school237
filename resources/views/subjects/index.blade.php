@extends('layouts.app')

@section('title', 'Sujets d\'évaluation')

@section('content')
<!-- Hero Section -->
@component('components.homepanel', 
    [
        'title' => 'Sujets d\'évaluation', 
        'description' => 'Parcourez notre collection de sujets d\'examen classés par niveau, matière, catégorie et plus.'])
@endcomponent
<div class="bg-white">
    <div class="px-4 sm:px-6 lg:px-8 py-3">
        <!-- Filters -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('subjects.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                    <!-- Catégorie -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select id="subject_id" name="subject_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les matières</option>
                            @foreach($filter_subjects as $subject)
                                <option value="{{ $subject->id }}" @if(request('subject_id') == $subject->id) selected @endif>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Niveau -->
                    <div>
                        <label for="level_id" class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                        <select id="level_id" name="level_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tout les niveaux</option>
                            @foreach($levels as $level)
                                <option value="{{ $level->id }}" @if(request('level_id') == $level->id) selected @endif>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Année -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                        <select id="year" name="year" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choisir une année</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" @if(request('year') == $year) selected @endif>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="bg-[#03386a] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filtrer
                    </button>

                    @if(request()->hasAny(['level_id', 'subject_id', 'year']))
                        <a href="{{ route('subjects.index') }}" 
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center">
                            Réinitialiser
                        </a>
                    @endif
                </div>

            </form>
        </div>
        
        
        <!-- Subjects list -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($subjects as $subject)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-200">
                <div class="w-full h-32 bg-green-100 flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded uppercase">
                            {{ $subject->type }}
                        </span>
                        @if($subject->subject->name)
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">{{ $subject->subject->name }}</span>
                        @endif
                        @if($subject->level->name)
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">{{ $subject->level->name }}</span>
                        @endif
                    </div>
                    
                    <h3 class="font-semibold mb-2 line-clamp-2">
                        <a href="{{ route('subjects.show', $subject->id) }}" class="text-gray-900 hover:text-green-600">
                            {{ $subject->title }}
                        </a>
                        <p class="text-gray-700 line-clamp-3">{{ Str::limit($subject->description, 140) }}</p>
                    </h3>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>{{ number_format($subject->file_size / 1024, 0) }} KB</span>
                        <span>{{ $subject->downloads_count }} téléchargements</span>
                    </div>
                </div>
            </div>
            
            @empty
                <div class="col-span-3">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun sujet trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Essayez de modifier vos critères de recherche.
                        </p>
                        @if(request()->hasAny(['level_id', 'subject_id', 'type', 'year', 'sort']))
                            <div class="mt-6">
                                <a href="{{ route('subjects.index') }}" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Voir tous les sujets
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($subjects->hasPages())
            <div class="mt-8">
                {{ $subjects->withQueryString()->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection
