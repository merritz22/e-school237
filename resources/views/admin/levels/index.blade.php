@extends('layouts.admin')

@section('title', 'Gestion des classes')

@section('content')
<div class="bg-white p-5">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des classes</h1>
                <p class="text-gray-600 mt-1">Gérez tous vos classes depuis cette interface</p>
            </div>
            
            <div class="flex items-center space-x-3 mt-4 md:mt-0">
                <a href="{{ route('admin.levels.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouvelle classe
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.levels.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4">
                <!-- System Filter -->
                <div>
                    <label for="system" class="block text-sm font-medium text-gray-700 mb-2">Système scolaire</label>
                    <select id="system" 
                            name="system" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les systèmes</option>
                        @foreach($systems as $system)
                            <option value="{{ $system }}" 
                                    @if(request('system') == $system) selected @endif>
                                {{ $system }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- School Filter -->
                <div>
                    <label for="school" class="block text-sm font-medium text-gray-700 mb-2">Type d'école</label>
                    <select id="school" 
                            name="school" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les écoles</option>
                        @foreach($schools as $school)
                            <option value="{{ $school }}" 
                                    @if(request('school') == $school) selected @endif>
                                {{ $school }}
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
                
                @if(request()->hasAny(['system', 'school']))
                    <a href="{{ route('admin.levels.index') }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                        Effacer les filtres
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!--e matières Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Système</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type d'école</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($levels as $level)
                        <tr class="hover:bg-gray-50">
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium">
                                    {{ $level->name }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium">
                                    {{ $level->system }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium">
                                    {{ $level->school }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium">
                                    {{ $level->description }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($level->is_active)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">

                                    <!-- Edit -->
                                    <a href="{{ route('admin.levels.edit', $level) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 p-1" 
                                       title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Publish/Unpublish -->
                                    <form action="{{ route('admin.levels.publish', $level) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="p-1 {{ $level->is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}" 
                                                title="{{ $level->is_active ? 'Désactiver' : 'Activer' }}">
                                            @if($level->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune classe trouvé</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premiere classe.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.levels.create') }}" 
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                        Créer une classe
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($levels->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $levels->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-50 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Confirmer la suppression</h2>
        <p class="text-gray-600 mb-6">
            Êtes-vous sûr de vouloir supprimer la classe 
            <span id="articleTitle" class="font-medium text-red-600"></span> ?
        </p>
        <div class="flex justify-end space-x-3">
            <button type="button" 
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                    onclick="closeDeleteModal()">
                Annuler
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-submit form on select change
document.querySelectorAll('select[name="status"], select[name="subject"], select[name="author"]').forEach(function(select) {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// Bulk actions
function selectAll() {
    const checkboxes = document.querySelectorAll('input[name="selected[]"]');
    const selectAllCheckbox = document.querySelector('input[name="select_all"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    toggleBulkActions();
}

function toggleBulkActions() {
    const selected = document.querySelectorAll('input[name="selected[]"]:checked');
    const bulkActions = document.getElementById('bulk-actions');
    
    if (selected.length > 0) {
        bulkActions.classList.remove('hidden');
    } else {
        bulkActions.classList.add('hidden');
    }
}

function openDeleteModal(actionUrl, title) {
    document.getElementById('deleteForm').setAttribute('action', actionUrl);
    document.getElementById('articleTitle').textContent = title;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}
</script>
@endpush
@endsection