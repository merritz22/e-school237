@extends('layouts.admin')

@section('title', 'Modifier un sujet d\'évaluation')

@section('content')
<div class="bg-white p-5 max-w-3xl mx-auto">

    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier le sujet</h1>
            <p class="text-gray-600 mt-1">Mettez à jour les informations du sujet</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" 
           class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour
        </a>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $subject->title) }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" 
                   placeholder="Entrez le titre du sujet">
            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                      placeholder="Description ou notes supplémentaires">{{ old('description', $subject->description) }}</textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Matière <span class="text-red-500">*</span></label>
            <select name="category_id" id="category_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                <option value="">Sélectionner une matière</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('subject_id', $subject->subject_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="level_id" class="block text-sm font-medium text-gray-700 mb-2">Niveau <span class="text-red-500">*</span></label>
            <select name="level_id" id="level_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('level_id') border-red-500 @enderror">
                <option value="">Sélectionner un niveau</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" @selected(old('level_id', $subject->level_id) == $level->id)>{{ $level->name }}</option>
                @endforeach
            </select>
            @error('level_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
            <select name="type" id="type" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                <option value="">Sélectionner un type</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(old('type', $subject->type) == $type)>{{ $type }}</option>
                @endforeach
            </select>
            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- <div>
            <label for="exam_date" class="block text-sm font-medium text-gray-700 mb-2">Date de l'examen</label>
            <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date', $subject->exam_date) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('exam_date') border-red-500 @enderror">
            @error('exam_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div> --}}

        <div>
            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes)</label>
            <input type="number" name="duration_minutes" id="duration_minutes" min="1" value="{{ old('duration_minutes', $subject->duration_minutes) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration_minutes') border-red-500 @enderror"
                   placeholder="Durée en minutes">
            @error('duration_minutes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="block">
            <!-- Fichier principal -->
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Fichier principal (pdf, doc, docx) <span class="text-red-500">*</span></label>
                @if($subject->file_path)
                    <div class="mb-2 flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ basename($subject->file_path) }}</span>
                        <a href="{{ route('admin.subjects.download', $subject) }}" class="ml-2 text-blue-600 hover:text-blue-800 text-sm">(Télécharger)</a>
                    </div>
                @endif
                <div class="mt-1 flex items-center">
                    <label for="file" class="relative cursor-pointer bg-white rounded-lg border border-gray-300 hover:border-blue-500 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500 @error('file') border-red-500 @enderror">
                        <div class="px-4 py-3 flex items-center">
                            <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="text-sm text-gray-700" id="file-name">Choisir un nouveau fichier</span>
                        </div>
                        <input type="file" name="file" id="file" accept=".pdf,.doc,.docx" class="sr-only">
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Formats acceptés: .pdf, .doc, .docx - Laissez vide pour conserver le fichier actuel</p>
                @error('file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Fichier de correction -->
            <div>
                <label for="correction_file" class="block text-sm font-medium text-gray-700 mb-2">Fichier de correction</label>
                @if($subject->correction_file_path)
                    <div class="mb-2 flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ basename($subject->correction_file_path) }}</span>
                        <a href="{{ route('admin.subjects.download', ['subject' => $subject, 'type' => 'correction']) }}" class="ml-2 text-blue-600 hover:text-blue-800 text-sm">(Télécharger)</a>
                    </div>
                @endif
                <div class="mt-1 flex items-center">
                    <label for="correction_file" class="relative cursor-pointer bg-white rounded-lg border border-gray-300 hover:border-blue-500 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500 @error('correction_file') border-red-500 @enderror">
                        <div class="px-4 py-3 flex items-center">
                            <svg class="w-6 h-6 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="text-sm text-gray-700" id="correction-file-name">Choisir un nouveau fichier</span>
                        </div>
                        <input type="file" name="correction_file" id="correction_file" accept=".pdf,.doc,.docx" class="sr-only">
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Formats acceptés: .pdf, .doc, .docx - Laissez vide pour conserver le fichier actuel</p>
                @error('correction_file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-between space-x-4 pt-4">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mettre à jour
            </button>

            <a href="{{ route('admin.subjects.index') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors flex items-center justify-center">
                Annuler
            </a>
        </div>
    </form>

</div>

@push('scripts')
<script>
    // Affiche le nom du fichier sélectionné
    document.getElementById('file').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un nouveau fichier';
        document.getElementById('file-name').textContent = fileName;
    });

    document.getElementById('correction_file').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un nouveau fichier';
        document.getElementById('correction-file-name').textContent = fileName;
    });
</script>
@endpush

@endsection