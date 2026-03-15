@extends('layouts.admin')

@section('title', 'éditer')

@section('content')
<div>
    <div class="p-6 space-y-6 max-w-4xl">

        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.level-subjects.index') }}"
               class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $level->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Sélectionnez les matières enseignées dans cette classe
                </p>
            </div>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('admin.level-subjects.update', $level) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm
                border border-gray-200 dark:border-zinc-700 p-6 space-y-6">

                {{-- Compteur sélection --}}
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 dark:text-zinc-300">
                        Matières disponibles
                    </span>
                    <span id="count" class="text-sm text-gray-400">
                        {{ count($linkedSubjectIds) }} sélectionnée(s)
                    </span>
                </div>

                {{-- Actions rapides --}}
                <div class="flex gap-2">
                    <button type="button" onclick="selectAll()"
                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-300
                            hover:bg-gray-50 dark:border-zinc-600 dark:hover:bg-zinc-800
                            text-gray-600 dark:text-zinc-300 transition-colors">
                        Tout sélectionner
                    </button>
                    <button type="button" onclick="deselectAll()"
                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-300
                            hover:bg-gray-50 dark:border-zinc-600 dark:hover:bg-zinc-800
                            text-gray-600 dark:text-zinc-300 transition-colors">
                        Tout désélectionner
                    </button>
                </div>

                {{-- Grille matières --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="subjects-grid">
                    @foreach($subjects as $subject)
                        @php $checked = in_array($subject->id, $linkedSubjectIds); @endphp
                        <label class="subject-card flex items-center gap-3 p-3 rounded-lg
                            border-2 cursor-pointer transition-all duration-150
                            {{ $checked
                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                                : 'border-gray-200 dark:border-zinc-700 hover:border-gray-300' }}">
                            <input
                                type="checkbox"
                                name="subject_ids[]"
                                value="{{ $subject->id }}"
                                {{ $checked ? 'checked' : '' }}
                                class="w-4 h-4 rounded text-blue-600
                                    border-gray-300 focus:ring-blue-500"
                                onchange="updateCard(this)"
                            />
                            <span class="text-sm font-medium text-gray-800 dark:text-zinc-200">
                                {{ $subject->name }}
                            </span>
                        </label>
                    @endforeach
                </div>

                {{-- Erreurs --}}
                @error('subject_ids')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- Boutons --}}
                <div class="flex items-center justify-between pt-4
                    border-t border-gray-100 dark:border-zinc-800">
                    <a href="{{ route('admin.level-subjects.index') }}"
                       class="px-4 py-2 text-sm rounded-lg border border-gray-300
                           dark:border-zinc-600 text-gray-600 dark:text-zinc-300
                           hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2 text-sm font-medium
                            rounded-lg bg-blue-600 hover:bg-blue-700
                            text-white transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Sauvegarder
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- JS natif --}}
    <script>
        function updateCard(checkbox) {
            const label = checkbox.closest('label');
            if (checkbox.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
                label.classList.remove('border-gray-200');
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-200');
            }
            updateCount();
        }

        function updateCount() {
            const checked = document.querySelectorAll('#subjects-grid input:checked').length;
            document.getElementById('count').textContent = checked + ' sélectionnée(s)';
        }

        function selectAll() {
            document.querySelectorAll('#subjects-grid input').forEach(cb => {
                cb.checked = true;
                updateCard(cb);
            });
        }

        function deselectAll() {
            document.querySelectorAll('#subjects-grid input').forEach(cb => {
                cb.checked = false;
                updateCard(cb);
            });
        }
    </script>
</div>
@endsection