@extends('layouts.admin')

@section('title', 'Sujets et classes')

@section('content')
<div>
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Matières par classe
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Gérez les matières associées à chaque classe
                </p>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-lg
                bg-green-50 border border-green-200 text-green-700 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm
            border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-zinc-800
                    border-b border-gray-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-zinc-300">
                            Classe
                        </th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600 dark:text-zinc-300">
                            Matières liées
                        </th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600 dark:text-zinc-300">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($levels as $level)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $level->name }}
                            </td>
                            <td class="px-6 py-4">
                                @if($level->subjects_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                        text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $level->subjects_count }} matière(s)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                        text-xs font-medium bg-red-100 text-red-700">
                                        Aucune matière
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.level-subjects.edit', $level) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5
                                       text-xs font-medium rounded-lg
                                       bg-blue-600 hover:bg-blue-700
                                       text-white transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Gérer
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                Aucune classe trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection