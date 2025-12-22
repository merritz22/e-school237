@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel utilisateur
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">&times;</button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <h6 class="text-sm font-semibold text-gray-700">Filtres</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                               placeholder="Nom, email..."
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                        <select id="role" name="role" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select id="status" name="status" 
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow text-sm">
                            Filtrer
                        </button>
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg shadow text-sm">
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-3 border-b border-gray-200">
            <h6 class="text-sm font-semibold text-gray-700">Liste des utilisateurs ({{ $users->total() }})</h6>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Avatar</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Nom complet</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Rôle</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Dernière connexion</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full">
                                @else
                                    <div class="w-10 h-10 flex items-center justify-center bg-indigo-500 text-white rounded-full">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <span class="font-semibold">{{ $user->full_name }}</span><br>
                                <span class="text-sm text-gray-500">{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                       ($user->role === 'moderator' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($user->role === 'author' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if($user->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactif</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Jamais' }}</td>
                            <td class="px-4 py-2 space-x-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs">Voir</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-xs">Modifier</a>
                                @if($user->is_active)
                                    <form id="suspendForm{{ $user->id }}" action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="button" 
                                            onclick="openSuspendModal({{ $user->id }})" 
                                            class="px-2 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs">
                                            Suspendre
                                        </button>
                                    </form>
                                @else
                                    <form id="activateForm-{{ $user->id }}" action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="openActivateModal({{ $user->id }})" 
                                                class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs">
                                            Activer
                                        </button>
                                    </form>

                                    <!-- Modal activation -->
                                    <div id="activateModal-{{ $user->id }}" 
                                        class="fixed inset-0 bg-gray-300 bg-opacity-40 hidden items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
                                            <h3 class="text-lg font-semibold mb-4">Confirmer l'activation</h3>
                                            <p class="mb-6">Êtes-vous sûr de vouloir activer cet utilisateur ?</p>
                                            <div class="flex justify-end space-x-4">
                                                <button type="button" onclick="closeActivateModal({{ $user->id }})" 
                                                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                                                <button type="button" onclick="submitActivateForm({{ $user->id }})" 
                                                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Activer</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <form id="deleteForm-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            onclick="openDeleteModal({{ $user->id }})" 
                                            class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs">
                                        Supprimer
                                    </button>
                                </form>
                                <!-- Modal suppression -->
                                <div id="deleteModal-{{ $user->id }}" 
                                    class="fixed inset-0 bg-gray-300 bg-opacity-40 hidden items-center justify-center z-50">
                                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
                                        <h3 class="text-lg font-semibold mb-4">Confirmer la suppression</h3>
                                        <p class="mb-6">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
                                        <div class="flex justify-end space-x-4">
                                            <button type="button" onclick="closeDeleteModal({{ $user->id }})" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                                            <button type="button" onclick="submitDeleteForm({{ $user->id }})" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-500">Aucun utilisateur trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Suspendre -->
<div id="suspendModal" class="fixed inset-0 bg-gray-300 bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
        <h2 class="text-lg font-semibold mb-4">Confirmation</h2>
        <p class="mb-6">Êtes-vous sûr de vouloir suspendre cet utilisateur ?</p>
        <div class="flex justify-end space-x-4">
            <button onclick="closeSuspendModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
            <button id="confirmSuspendBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Suspendre</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentSuspendFormId = null;

    function openSuspendModal(userId) {
        currentSuspendFormId = 'suspendForm' + userId;
        document.getElementById('suspendModal').classList.remove('hidden');
        document.getElementById('suspendModal').classList.add('flex');
    }

    function closeSuspendModal() {
        document.getElementById('suspendModal').classList.add('hidden');
        document.getElementById('suspendModal').classList.remove('flex');
        currentSuspendFormId = null;
    }

    document.getElementById('confirmSuspendBtn').addEventListener('click', function() {
        if (currentSuspendFormId) {
            document.getElementById(currentSuspendFormId).submit();
        }
    });

    // Fermer la modale si clic en dehors du contenu
    document.getElementById('suspendModal').addEventListener('click', function(e) {
        if (e.target === this) closeSuspendModal();
    });


    function openDeleteModal(userId) {
        const modal = document.getElementById('deleteModal-' + userId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Ajoute flex pour centrer verticalement/horizontalement
            document.body.classList.add('overflow-hidden'); // Bloque scroll arrière-plan
        }
    }

    function closeDeleteModal(userId) {
        const modal = document.getElementById('deleteModal-' + userId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function submitDeleteForm(userId) {
        const form = document.getElementById('deleteForm-' + userId);
        if (form) {
            form.submit();
        }
    }

    function openActivateModal(userId) {
        const modal = document.getElementById('activateModal-' + userId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeActivateModal(userId) {
        const modal = document.getElementById('activateModal-' + userId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function submitActivateForm(userId) {
        const form = document.getElementById('activateForm-' + userId);
        if (form) {
            form.submit();
        }
    }
</script>
@endpush

