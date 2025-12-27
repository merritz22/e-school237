@extends('layouts.app')

@section('title', $subject->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    
    <!-- Titre -->
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $subject->title }}</h1>

    <!-- Description -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Description</h2>
        <p class="text-gray-600">{{ $subject->description ?: 'Aucune description.' }}</p>
    </div>

    <!-- Informations principales -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Informations</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-gray-700">
            <p><span class="font-medium">Niveau :</span> {{ $subject->level->name ?? $subject->level_id }}</p>
            {{-- <p><span class="font-medium">Matière :</span> {{ $subject->subject_name ?? $subject->subject_id }}</p> --}}
            <p><span class="font-medium">Matière :</span> {{ $subject->category->name ?? 'N/A' }}</p>
            <p><span class="font-medium">Type :</span> {{ $subject->type }}</p>
            <p><span class="font-medium">Durée :</span> {{ $subject->formatted_duration ?? '-' }}</p>
            {{-- <p><span class="font-medium">Année :</span> {{ $subject->exam_date ? $subject->exam_date->format('Y') : '-' }}</p> --}}
            <p><span class="font-medium">Téléchargements :</span> {{ $subject->downloads_count }}</p>
        </div>
    </div>

    <!-- Boutons de téléchargement -->
    <div class="flex items-center gap-3 mb-8">
        @auth
            <a href="{{ route('subjects.download', $subject) }}" 
               class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow">
                📄 Télécharger le sujet
            </a>
            {{-- @if($subject->correction_file_path)
                <a href="{{ route('subjects.download', ['subject' => $subject->id, 'file' => 'correction']) }}" 
                   class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                    📝 Télécharger la correction
                </a>
            @endif --}}
        @else
            <p class="text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 underline">Connectez-vous</a> 
                pour télécharger ce sujet.
            </p>
        @endauth
    </div>

    <!-- Sujets similaires -->
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Sujets similaires</h2>
        @if($related_subjects->count())
            <ul class="divide-y divide-gray-200">
                @foreach($related_subjects as $related)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <a href="{{ route('subjects.show', $related) }}" 
                               class="text-blue-600 font-medium hover:underline">
                                {{ $related->title }}
                            </a>
                            <p class="text-sm text-gray-500">
                                {{ $related->type }}, {{ $related->level->name ?? $related->level_id }}
                            </p>
                        </div>
                        <a href="{{ route('subjects.show', $related) }}" 
                           class="text-sm text-gray-400 hover:text-gray-600">
                            ➜ Voir
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">Aucun sujet similaire trouvé.</p>
        @endif
    </div>
</div>
@endsection
