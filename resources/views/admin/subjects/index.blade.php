@extends('layouts.admin')

@section('title', 'Gestion des sujets d\'évaluation')

@section('content')
<div class="bg-white p-5">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des sujets d'évaluation</h1>
                <p class="text-gray-600 mt-1">Gérez tous vos sujets d'évaluation depuis cette interface</p>
            </div>

            <div class="flex items-center space-x-3 mt-4 md:mt-0">
                <a href="{{ route('admin.subjects.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau sujet
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.subjects.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Filename Filter -->
                <div>
                    <label for="file_name" class="block text-sm font-medium text-gray-700 mb-2">Nom du fichier</label>
                    <input type="text"
                           id="file_name"
                           name="file_name"
                           value="{{ request('file_name') }}"
                           placeholder="Ex : sujet-maths.pdf"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Subject Filter -->
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                    <select id="subject_id"
                            name="subject_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les Matières</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                    @if(request('subject_id') == $subject->id) selected @endif>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Level Filter -->
                <div>
                    <label for="level_id" class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <select id="level_id"
                            name="level_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les niveaux</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}"
                                    @if(request('level_id') == $level->id) selected @endif>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Author Filter -->
                <div>
                    <label for="author_id" class="block text-sm font-medium text-gray-700 mb-2">Auteur</label>
                    <select id="author_id"
                            name="author_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les auteurs</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}"
                                    @if(request('author_id') == $author->id) selected @endif>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Rechercher
                </button>

                @if(request()->hasAny(['file_name', 'subject_id', 'level_id', 'author_id']))
                    <a href="{{ route('admin.subjects.index') }}"
                       class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                        Effacer les filtres
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total sujets</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $evalsubjects->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total téléchargements</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($evalsubjects->sum('downloads_count')) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .672-3 1.5S10.343 11 12 11s3 .672 3 1.5-1.343 1.5-3 1.5m0-6V6m0 9v1.5m0-1.5c-1.657 0-3-.672-3-1.5M12 6c1.657 0 3 .672 3 1.5"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gratuits</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $evalsubjects->where('is_free', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Payants</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $evalsubjects->where('is_free', false)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sujet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléchargements</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $typeLabels = ['exam' => 'Examen', 'quiz' => 'Quiz', 'exercise' => 'Exercice', 'qcm' => 'QCM'];
                    @endphp
                    @forelse($evalsubjects as $evalsubject)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 max-w-xs">
                                <div class="flex items-center">
                                    @if($evalsubject->preview_image)
                                        <img src="{{ Storage::url($evalsubject->preview_image) }}"
                                             alt="{{ $evalsubject->title }}"
                                             class="w-12 h-12 rounded-lg object-cover mr-4 shrink-0">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 break-words">
                                            {{ $evalsubject->title }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $evalsubject->formatted_file_size }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="{{ $evalsubject->author && $evalsubject->author->avatar ? Storage::url($evalsubject->author->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($evalsubject->author->name ?? '?') . '&background=3b82f6&color=fff' }}"
                                         alt="{{ $evalsubject->author->name ?? '-' }}"
                                         class="w-8 h-8 rounded-full mr-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $evalsubject->author->name ?? '-' }}</div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ $evalsubject->subject->name ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ $evalsubject->level->name ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                    {{ $typeLabels[$evalsubject->type] ?? ucfirst($evalsubject->type) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($evalsubject->downloads_count) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $evalsubject->created_at->format('d/m/Y') }} {{ $evalsubject->created_at->format('H:i') }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <!-- View -->
                                    <a href="{{ route('subjects.show', $evalsubject) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900 p-1"
                                       title="Voir le sujet">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    <!-- Download -->
                                    <a href="{{ route('admin.subjects.download', $evalsubject) }}"
                                       class="text-green-600 hover:text-green-900 p-1"
                                       title="Télécharger">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('admin.subjects.edit', $evalsubject) }}"
                                       class="text-indigo-600 hover:text-indigo-900 p-1"
                                       title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.subjects.destroy', $evalsubject) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce sujet ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 p-1"
                                                title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                                        01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1
                                                        0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun sujet trouvé</h3>
                                <p class="mt-1 text-sm text-gray-500">Essayez de modifier vos critères de recherche.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.subjects.create') }}"
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        Créer un sujet
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($evalsubjects->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $evalsubjects->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('select[name="subject_id"], select[name="level_id"], select[name="author_id"]').forEach(function(select) {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
@endsection
