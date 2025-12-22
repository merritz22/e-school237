@extends('layouts.admin')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-gray-800 mb-0">Profil de {{ $user->full_name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded">
                <i class="fas fa-edit fa-sm text-white/50 mr-1"></i> Modifier
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded">
                <i class="fas fa-arrow-left fa-sm text-white/50 mr-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        <!-- Informations principales -->
        <div class="w-full xl:w-1/3 lg:w-5/12 px-4 mb-4">
            <div class="bg-white rounded shadow p-6 text-center">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="rounded-full mb-3 mx-auto" style="width: 120px; height: 120px;">
                @else
                    <div class="bg-blue-600 text-white rounded-full mx-auto mb-3 flex items-center justify-center" 
                         style="width: 120px; height: 120px; font-size: 48px;">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="text-xl font-semibold">{{ $user->full_name }}</h5>
                <p class="text-gray-500">{{ $user->email }}</p>

                <div class="mt-4 mb-3">
                    <span class="inline-block px-3 py-1 text-sm font-medium rounded
                        {{ $user->role === 'admin' ? 'bg-red-600 text-white' : 
                           ($user->role === 'moderator' ? 'bg-yellow-400 text-black' : 
                           ($user->role === 'author' ? 'bg-blue-400 text-white' : 'bg-gray-400 text-white')) }}">
                        {{ ucfirst($user->role) }}
                    </span>

                    @if($user->is_active)
                        <span class="inline-block ml-2 px-3 py-1 text-sm font-medium rounded bg-green-600 text-white">Actif</span>
                    @else
                        <span class="inline-block ml-2 px-3 py-1 text-sm font-medium rounded bg-red-600 text-white">Inactif</span>
                    @endif
                </div>

                @if($user->bio)
                    <div class="text-left">
                        <h6 class="text-blue-600 font-semibold mb-1">Biographie</h6>
                        <p class="text-gray-500">{{ $user->bio }}</p>
                    </div>
                @endif

                <div class="text-left mt-6">
                    <h6 class="text-blue-600 font-semibold mb-3">Actions rapides</h6>
                    <div class="flex flex-col space-y-2 w-full">
                        @if($user->is_active)
                            <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded flex items-center justify-center"
                                        onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                                    <i class="fas fa-ban mr-2"></i> Suspendre
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded flex items-center justify-center">
                                    <i class="fas fa-check mr-2"></i> Activer
                                </button>
                            </form>
                        @endif

                        <button type="button" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded flex items-center justify-center"
                                onclick="changeRole({{ $user->id }}, '{{ $user->role }}')">
                            <i class="fas fa-user-tag mr-2"></i> Changer le rôle
                        </button>

                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded flex items-center justify-center"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations détaillées -->
        <div class="w-full xl:w-2/3 lg:w-7/12 px-4 mb-4">
            <!-- Informations générales -->
            <div class="bg-white rounded shadow mb-4">
                <div class="px-6 py-3 border-b border-gray-200">
                    <h6 class="font-semibold text-blue-600 m-0">Informations générales</h6>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap -mx-4">
                        <div class="w-full sm:w-1/2 px-4">
                            <table class="w-full border-collapse">
                                <tbody>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Nom d'utilisateur:</th>
                                        <td class="py-2">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Prénom:</th>
                                        <td class="py-2">{{ $user->first_name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Nom:</th>
                                        <td class="py-2">{{ $user->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Email:</th>
                                        <td class="py-2">{{ $user->email }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="w-full sm:w-1/2 px-4">
                            <table class="w-full border-collapse">
                                <tbody>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Rôle:</th>
                                        <td class="py-2">
                                            <span class="inline-block px-3 py-1 text-sm font-medium rounded
                                                {{ $user->role === 'admin' ? 'bg-red-600 text-white' : 
                                                   ($user->role === 'moderator' ? 'bg-yellow-400 text-black' : 
                                                   ($user->role === 'author' ? 'bg-blue-400 text-white' : 'bg-gray-400 text-white')) }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Statut:</th>
                                        <td class="py-2">
                                            @if($user->is_active)
                                                <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-green-600 text-white">Actif</span>
                                            @else
                                                <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-red-600 text-white">Inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Inscription:</th>
                                        <td class="py-2">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left pr-4 w-2/5 py-2">Dernière connexion:</th>
                                        <td class="py-2">{{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Jamais' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques d'activité -->
            <div class="bg-white rounded shadow mb-4">
                <div class="px-6 py-3 border-b border-gray-200">
                    <h6 class="font-semibold text-blue-600 m-0">Statistiques d'activité</h6>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap text-center -mx-3">
                        <div class="w-1/2 md:w-1/4 px-3 mb-3">
                            <div class="bg-blue-700 text-white rounded p-4 relative">
                                <div class="absolute top-[-25px] right-[-25px] text-5xl transform rotate-15 opacity-20 pointer-events-none">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div>
                                    <h5 class="text-xl font-semibold">{{ $user->articles->count() }}</h5>
                                    <p class="mb-0">Articles</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 md:w-1/4 px-3 mb-3">
                            <div class="bg-green-700 text-white rounded p-4 relative">
                                <div class="absolute top-[-25px] right-[-25px] text-5xl transform rotate-15 opacity-20 pointer-events-none">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <h5 class="text-xl font-semibold">{{ $user->evaluationSubjects->count() }}</h5>
                                    <p class="mb-0">Sujets d'évaluation</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 md:w-1/4 px-3 mb-3">
                            <div class="bg-blue-400 text-white rounded p-4 relative">
                                <div class="absolute top-[-25px] right-[-25px] text-5xl transform rotate-15 opacity-20 pointer-events-none">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <h5 class="text-xl font-semibold">{{ $user->educationalResources->count() }}</h5>
                                    <p class="mb-0">Ressources</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2 md:w-1/4 px-3 mb-3">
                            <div class="bg-yellow-500 text-white rounded p-4 relative">
                                <div class="absolute top-[-25px] right-[-25px] text-5xl transform rotate-15 opacity-20 pointer-events-none">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div>
                                    <h5 class="text-xl font-semibold">{{ $user->forumTopics->count() + $user->forumReplies->count() }}</h5>
                                    <p class="mb-0">Posts forum</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activité récente -->
            <div class="bg-white rounded shadow mb-4">
                <div class="px-6 py-3 border-b border-gray-200">
                    <h6 class="font-semibold text-blue-600 m-0">Activité récente</h6>
                </div>
                <div class="p-6">
                    @php
                        $recentArticles = $user->articles()->latest()->take(5)->get();
                        $recentTopics = $user->forumTopics()->latest()->take(5)->get();
                        $recentResources = $user->educationalResources()->latest()->take(5)->get();
                    @endphp

                    @if($recentArticles->count() > 0)
                        <h6 class="text-blue-600 font-semibold mb-2">Articles récents</h6>
                        <ul class="divide-y divide-gray-200 mb-3">
                            @foreach($recentArticles as $article)
                                <li class="flex justify-between items-center py-2 px-0">
                                    <div>
                                        <strong>{{ $article->title }}</strong>
                                        <br><small class="text-gray-500">{{ $article->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <span class="inline-block px-2 py-1 rounded text-sm
                                        {{ $article->is_published ? 'bg-green-600 text-white' : 'bg-gray-400 text-white' }}">
                                        {{ $article->is_published ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($recentTopics->count() > 0)
                        <h6 class="text-blue-600 font-semibold mb-2">Topics de forum récents</h6>
                        <ul class="divide-y divide-gray-200 mb-3">
                            @foreach($recentTopics as $topic)
                                <li class="flex justify-between items-center py-2 px-0">
                                    <div>
                                        <strong>{{ $topic->title }}</strong>
                                        <br><small class="text-gray-500">{{ $topic->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($recentResources->count() > 0)
                        <h6 class="text-blue-600 font-semibold mb-2">Ressources récentes</h6>
                        <ul class="divide-y divide-gray-200 mb-3">
                            @foreach($recentResources as $resource)
                                <li class="flex justify-between items-center py-2 px-0">
                                    <div>
                                        <strong>{{ $resource->title }}</strong>
                                        <br><small class="text-gray-500">{{ $resource->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <span class="inline-block bg-blue-400 text-white rounded px-2 py-1 text-sm">
                                        {{ ucfirst($resource->type) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($recentArticles->count() === 0 && $recentTopics->count() === 0 && $recentResources->count() === 0)
                        <p class="text-gray-500 text-center">Aucune activité récente</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changement de rôle -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le rôle de {{ $user->full_name }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="roleForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modalRole">Nouveau rôle</label>
                        <select class="form-control" id="modalRole" name="role" required>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="moderator" {{ $user->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                            <option value="author" {{ $user->role === 'author' ? 'selected' : '' }}>Author</option>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Attention:</strong> Cette action modifiera les permissions de l'utilisateur.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer le changement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.card-body-icon {
    position: absolute;
    z-index: 0;
    top: -25px;
    right: -25px;
    font-size: 5rem;
    -webkit-transform: rotate(15deg);
    -ms-transform: rotate(15deg);
    transform: rotate(15deg);
}

.table th {
    border: none;
    padding: 0.5rem 0.75rem;
    width: 40%;
}

.table td {
    border: none;
    padding: 0.5rem 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
function changeRole(userId, currentRole) {
    const form = document.getElementById('roleForm');
    const select = document.getElementById('modalRole');
    
    form.action = `/admin/users/${userId}/role`;
    select.value = currentRole;
    
    $('#roleModal').modal('show');
}
</script>
@endpush
