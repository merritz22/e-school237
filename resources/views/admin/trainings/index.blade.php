@extends('layouts.admin')

@section('title', 'Gestion des formations')

@section('content')
<div class="bg-white p-5">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des formations</h1>
                <p class="text-gray-600 mt-1">Créez et gérez les formations proposées sur la plateforme</p>
            </div>

            <a href="{{ route('admin.trainings.create') }}"
               class="mt-4 md:mt-0 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nouvelle formation
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.trainings.index') }}" class="flex flex-col sm:flex-row gap-4 sm:items-end">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select id="status" name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="published" @if(request('status') === 'published') selected @endif>Publié</option>
                    <option value="draft" @if(request('status') === 'draft') selected @endif>Brouillon</option>
                    <option value="disabled" @if(request('status') === 'disabled') selected @endif>Désactivé</option>
                </select>
            </div>

            @if(request()->hasAny(['status']))
                <a href="{{ route('admin.trainings.index') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                    Effacer les filtres
                </a>
            @endif
        </form>
    </div>

    <!-- Trainings Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($trainings as $training)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 max-w-xs">
                                <div class="flex items-center">
                                    @if($training->banner)
                                        <img src="{{ $training->banner_url }}"
                                             alt="{{ $training->title }}"
                                             class="w-12 h-12 rounded-lg object-cover mr-4 shrink-0">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 20.5a12.083 12.083 0 01-6.16-9.922L12 14z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 break-words">{{ $training->title }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $training->duration }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($training->price, 0, ',', ' ') }} FCFA
                                @if($training->original_price)
                                    <span class="text-xs text-gray-400 line-through ml-1">{{ number_format($training->original_price, 0, ',', ' ') }}</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($training->status === 'published')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Publié</span>
                                @elseif($training->status === 'disabled')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Désactivé</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Brouillon</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $training->created_at->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($training->isPublished())
                                        <a href="{{ route('trainings.show', $training) }}"
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900 p-1"
                                           title="Voir la formation">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.trainings.edit', $training) }}"
                                       class="text-indigo-600 hover:text-indigo-900 p-1"
                                       title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    @if($training->isPublished())
                                        <form action="{{ route('admin.trainings.disable', $training) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-orange-600 hover:text-orange-900 p-1" title="Désactiver">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.trainings.publish', $training) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900 p-1" title="Publier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.trainings.destroy', $training) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la formation « {{ $training->title }} » ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune formation trouvée</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par créer votre première formation.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.trainings.create') }}"
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        Créer une formation
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trainings->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $trainings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
