@extends('layouts.app')

@section('title', 'Sujets d\'évaluation')

@section('content')
<div class="bg-white">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold mb-4">Sujets d'évaluation</h1>
            <p class="text-xl text-blue-100 max-w-2xl">
                Parcourez notre collection de sujets d'examen classés par niveau, matière, catégorie et plus.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <form method="GET" action="{{ route('subjects.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Niveau -->
                    {{-- <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                        <select id="level" name="level" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous</option>
                            @foreach($levels as $level)
                                <option value="{{ $level }}" @if(request('level') === $level) selected @endif>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <!-- Matière -->
                    {{-- <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select id="subject" name="subject" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes</option>
                            @foreach($subject_names as $subjectName)
                                <option value="{{ $subjectName }}" @if(request('subject') === $subjectName) selected @endif>{{ $subjectName }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <!-- Catégorie -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                        <select id="category" name="category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les matières</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" @if(request('category') === $category->slug) selected @endif>
                                    {{ $category->name }} ({{ $category->subjects_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select id="type" name="type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tous les types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" @if(request('type') === $type) selected @endif>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Année -->
                    {{-- <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Année</label>
                        <select id="year" name="year" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les années</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" @if(request('year') === $year) selected @endif>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <!-- Trier -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Trier</label>
                        <select id="sort" name="sort" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="latest" @if(request('sort') === 'latest') selected @endif>Derniers</option>
                            <option value="popular" @if(request('sort') === 'popular') selected @endif>Populaires</option>
                            <option value="title" @if(request('sort') === 'title') selected @endif>Titre</option>
                            {{-- <option value="level" @if(request('sort') === 'level') selected @endif>Niveau</option> --}}
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filtrer
                    </button>

                    @if(request()->hasAny(['level', 'subject', 'category', 'type', 'year', 'sort']))
                        <a href="{{ route('subjects.index') }}" 
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>

        
        <!-- Results Info -->
        <div class="flex justify-between items-center mb-6">

            <div class="text-gray-600 mb-6">
                @if($subjects->total() > 0)
                    {{ $subjects->firstItem() }} - {{ $subjects->lastItem() }} sur {{ number_format($subjects->total()) }} sujets
                @else
                    Aucun sujet trouvé
                @endif
            </div>
            
                
            <!-- View Toggle -->
            {{-- <div class="flex bg-gray-200 rounded-lg p-1" x-data="{ view: 'grid' }">
                <button @click="view = 'grid'" 
                        :class="view === 'grid' ? 'bg-white shadow-sm' : ''"
                        class="px-3 py-1 rounded text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
                <button @click="view = 'list'" 
                        :class="view === 'list' ? 'bg-white shadow-sm' : ''"
                        class="px-3 py-1 rounded text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </button>
            </div> --}}
        </div>
        
        
        <!-- Subjects list -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($subjects as $subject)
            <a href="{{ route('subjects.show', $subject) }}" 
            class="block bg-white rounded-lg shadow-sm hover:shadow-md border border-gray-200 overflow-hidden transition">
            <div class="p-6">
                <div class="relative w-full h-48 mb-4 border rounded overflow-hidden">
                    <iframe 
                        src="{{ url('/pdf/' . $subject->id) }}#toolbar=0&navpanes=0"
                        class="w-full h-full"
                        loading="lazy">
                    </iframe>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="text-blue-600/20 text-5xl font-bold rotate-[-30deg] select-none">
                            E-School237
                        </span>
                    </div>
                </div>
                <h3 class="font-semibold text-xl mb-2 line-clamp-2">{{ $subject->title }}</h3>
                <div class="text-sm text-gray-600 mb-3 space-y-1">
                    {{-- @dd($subject) --}}
                    <div><strong>Niveau :</strong> {{ $subject->level->name ?? 0 }}</div>
                    <div><strong>Matière :</strong> {{ $subject->category->name ?? 'N/A' }}</div>
                    <div><strong>Type :</strong> {{ $subject->type }}</div>
                    {{-- <div><strong>Année :</strong> {{ $subject->exam_date ? $subject->exam_date->format('Y') : '-' }}</div> --}}
                    <div><strong>Téléchargements :</strong> {{ $subject->downloads_count }}</div>
                </div>
                <p class="text-gray-700 line-clamp-3">{{ Str::limit($subject->description, 140) }}</p>
            </div>
            </a>
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
                        @if(request()->hasAny(['level', 'subject', 'category', 'type', 'year', 'sort']))
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
