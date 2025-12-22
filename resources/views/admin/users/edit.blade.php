@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-gray-800 mb-0">Modifier {{ $user->full_name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded">
                <i class="fas fa-eye fa-sm text-white/50 mr-1"></i> Voir le profil
            </a>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded">
                <i class="fas fa-arrow-left fa-sm text-white/50 mr-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <ul class="list-disc pl-5 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
            {{ session('success') }}
            <button type="button" class="absolute top-1 right-2 text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
                &times;
            </button>
        </div>
    @endif

    <div class="bg-white shadow rounded mb-6">
        <div class="border-b border-gray-200 px-6 py-3">
            <h6 class="text-blue-600 font-semibold m-0">Modifier les informations de l'utilisateur</h6>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="flex flex-wrap -mx-3">
                    <!-- Informations personnelles -->
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <h6 class="text-blue-600 font-semibold mb-3">Informations personnelles</h6>

                        <div class="mb-4">
                            <label for="first_name" class="block mb-1 font-medium required">Prénom</label>
                            <input type="text" id="first_name" name="first_name" required
                                class="w-full border rounded px-3 py-2 @error('first_name') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('first_name', $user->first_name) }}">
                            @error('first_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="last_name" class="block mb-1 font-medium required">Nom</label>
                            <input type="text" id="last_name" name="last_name" required
                                class="w-full border rounded px-3 py-2 @error('last_name') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('last_name', $user->last_name) }}">
                            @error('last_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block mb-1 font-medium required">Nom d'utilisateur</label>
                            <input type="text" id="name" name="name" required
                                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('name', $user->name) }}">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block mb-1 font-medium required">Email</label>
                            <input type="email" id="email" name="email" required
                                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="bio" class="block mb-1 font-medium">Biographie</label>
                            <textarea id="bio" name="bio" rows="4" maxlength="1000"
                                class="w-full border rounded px-3 py-2 @error('bio') border-red-500 @else border-gray-300 @enderror">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-gray-600">Maximum 1000 caractères</small>
                        </div>
                    </div>

                    <!-- Paramètres de compte -->
                    <div class="w-full md:w-1/2 px-3">
                        <h6 class="text-blue-600 font-semibold mb-3">Paramètres de compte</h6>

                        <div class="mb-4">
                            <label for="password" class="block mb-1 font-medium">Nouveau mot de passe</label>
                            <input type="password" id="password" name="password"
                                class="w-full border rounded px-3 py-2 @error('password') border-red-500 @else border-gray-300 @enderror">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-gray-600">Laissez vide pour conserver le mot de passe actuel. Minimum 8 caractères si renseigné.</small>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block mb-1 font-medium">Confirmer le nouveau mot de passe</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full border rounded px-3 py-2 @error('password_confirmation') border-red-500 @else border-gray-300 @enderror">
                            @error('password_confirmation')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block mb-1 font-medium required">Rôle</label>
                            <select id="role" name="role" required
                                class="w-full border rounded px-3 py-2 @error('role') border-red-500 @else border-gray-300 @enderror">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if($user->isAdmin() && \App\Models\User::byRole('admin')->count() === 1)
                                <p class="text-yellow-600 mt-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Attention : C'est le dernier administrateur du système
                                </p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    class="form-checkbox h-5 w-5 text-blue-600" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2">Compte actif</label>
                            </div>
                            <small class="text-gray-600 block mt-1">Si décoché, l'utilisateur ne pourra pas se connecter</small>
                            @if($user->isAdmin() && \App\Models\User::byRole('admin')->where('is_active', true)->count() === 1)
                                <p class="text-yellow-600 mt-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Attention : C'est le dernier administrateur actif
                                </p>
                            @endif
                        </div>

                        <!-- Description des rôles -->
                        <div class="bg-gray-100 rounded p-4 mt-6">
                            <h6 class="text-blue-600 font-semibold mb-2">Description des rôles</h6>
                            <ul class="list-disc pl-5 text-gray-700">
                                <li><strong>Admin :</strong> Accès complet à toutes les fonctionnalités</li>
                                <li><strong>Moderator :</strong> Peut modérer le contenu et gérer les utilisateurs</li>
                                <li><strong>Author :</strong> Peut créer et publier du contenu</li>
                                <li><strong>User :</strong> Accès de base en lecture</li>
                            </ul>
                        </div>

                        <!-- Informations de compte -->
                        <div class="bg-blue-100 text-blue-900 rounded p-4 mt-6">
                            <h6 class="font-semibold mb-2">Informations de compte</h6>
                            <ul class="list-disc pl-5">
                                <li><strong>Inscription :</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                                <li><strong>Dernière connexion :</strong> {{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : 'Jamais' }}</li>
                                <li><strong>Dernière modification :</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-gray-300">

                <div class="flex flex-wrap items-center justify-between">
                    <div class="space-x-2 flex flex-wrap items-center">
                        <button type="submit" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-save mr-2"></i> Sauvegarder les modifications
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            <i class="fas fa-eye mr-2"></i> Voir le profil
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                    </div>

                    <div class="space-x-2">
                        @if($user->is_active)
                            <button type="button" onclick="suspendUser({{ $user->id }})"
                                class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                                <i class="fas fa-ban mr-2"></i> Suspendre
                            </button>
                        @else
                            <button type="button" onclick="activateUser({{ $user->id }})"
                                class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                                <i class="fas fa-check mr-2"></i> Activer
                            </button>
                        @endif

                        <button type="button" onclick="deleteUser({{ $user->id }})"
                            class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-trash mr-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="bg-white shadow rounded mb-6">
        <div class="border-b border-gray-200 px-6 py-3">
            <h6 class="text-blue-600 font-semibold m-0">Statistiques d'activité</h6>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap text-center">
                <div class="w-1/2 md:w-1/4 mb-6 md:mb-0">
                    <div class="border rounded p-4">
                        <h4 class="text-blue-600">{{ $user->articles()->count() }}</h4>
                        <p class="text-gray-500 m-0">Articles</p>
                    </div>
                </div>
                <div class="w-1/2 md:w-1/4 mb-6 md:mb-0">
                    <div class="border rounded p-4">
                        <h4 class="text-green-600">{{ $user->evaluationSubjects()->count() }}</h4>
                        <p class="text-gray-500 m-0">Sujets d'évaluation</p>
                    </div>
                </div>
                <div class="w-1/2 md:w-1/4 mb-6 md:mb-0">
                    <div class="border rounded p-4">
                        <h4 class="text-blue-400">{{ $user->educationalResources()->count() }}</h4>
                        <p class="text-gray-500 m-0">Ressources éducatives</p>
                    </div>
                </div>
                <div class="w-1/2 md:w-1/4">
                    <div class="border rounded p-4">
                        <h4 class="text-yellow-500">{{ $user->forumTopics()->count() + $user->forumReplies()->count() }}</h4>
                        <p class="text-gray-500 m-0">Posts forum</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulaires cachés pour les actions rapides -->
<form id="suspendForm" action="{{ route('admin.users.suspend', $user) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>

<form id="activateForm" action="{{ route('admin.users.activate', $user) }}" method="POST" class="hidden">
    @csrf
    @method('PATCH')
</form>

<form id="deleteForm" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.required::after {
    content: " *";
    color: red;
}
</style>
@endpush

@push('scripts')
<script>
function suspendUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')) {
        document.getElementById('suspendForm').submit();
    }
}

function activateUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir activer cet utilisateur ?')) {
        document.getElementById('activateForm').submit();
    }
}

function deleteUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
        document.getElementById('deleteForm').submit();
    }
}

// Validation du mot de passe
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    function validatePasswords() {
        if (passwordInput.value && confirmPasswordInput.value) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);
});
</script>
@endpush
