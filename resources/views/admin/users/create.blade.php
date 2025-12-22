@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold text-gray-800 mb-0">Créer un utilisateur</h1>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded">
            <i class="fas fa-arrow-left fa-sm text-white/50 mr-1"></i> Retour à la liste
        </a>
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

    <div class="bg-white shadow rounded mb-6">
        <div class="border-b border-gray-200 px-6 py-3">
            <h6 class="text-blue-600 font-semibold m-0">Informations de l'utilisateur</h6>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" novalidate>
                @csrf

                <div class="flex flex-wrap -mx-3">
                    <!-- Informations personnelles -->
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <h6 class="text-blue-600 font-semibold mb-3">Informations personnelles</h6>

                        <div class="mb-4">
                            <label for="first_name" class="block mb-1 font-medium required">Prénom</label>
                            <input type="text" id="first_name" name="first_name" required
                                class="w-full border rounded px-3 py-2 @error('first_name') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('first_name') }}">
                            @error('first_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="last_name" class="block mb-1 font-medium required">Nom</label>
                            <input type="text" id="last_name" name="last_name" required
                                class="w-full border rounded px-3 py-2 @error('last_name') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('last_name') }}">
                            @error('last_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block mb-1 font-medium required">Nom d'utilisateur</label>
                            <input type="text" id="name" name="name" required
                                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block mb-1 font-medium required">Email</label>
                            <input type="email" id="email" name="email" required
                                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @else border-gray-300 @enderror"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="bio" class="block mb-1 font-medium">Biographie</label>
                            <textarea id="bio" name="bio" rows="4" maxlength="1000"
                                class="w-full border rounded px-3 py-2 @error('bio') border-red-500 @else border-gray-300 @enderror">{{ old('bio') }}</textarea>
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
                            <label for="password" class="block mb-1 font-medium required">Mot de passe</label>
                            <input type="password" id="password" name="password" required
                                class="w-full border rounded px-3 py-2 @error('password') border-red-500 @else border-gray-300 @enderror">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <small class="text-gray-600">Minimum 8 caractères</small>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block mb-1 font-medium required">Confirmer le mot de passe</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full border rounded px-3 py-2 @error('password_confirmation') border-red-500 @else border-gray-300 @enderror">
                            @error('password_confirmation')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block mb-1 font-medium required">Rôle</label>
                            <select id="role" name="role" required
                                class="w-full border rounded px-3 py-2 @error('role') border-red-500 @else border-gray-300 @enderror">
                                <option value="">Sélectionner un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    class="form-checkbox h-5 w-5 text-blue-600" {{ old('is_active', 1) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2">Compte actif</label>
                            </div>
                            <small class="text-gray-600 block mt-1">Si décoché, l'utilisateur ne pourra pas se connecter</small>
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
                    </div>
                </div>

                <hr class="my-6 border-gray-300">

                <div class="mb-0 flex flex-wrap items-center space-x-2">
                    <button type="submit" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        <i class="fas fa-save mr-2"></i> Créer l'utilisateur
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Auto-génération du nom d'utilisateur basé sur prénom/nom
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const usernameInput = document.getElementById('name');

    function generateUsername() {
        const firstName = firstNameInput.value.trim().toLowerCase();
        const lastName = lastNameInput.value.trim().toLowerCase();

        if (firstName && lastName) {
            const username = firstName + '.' + lastName;
            if (!usernameInput.value || usernameInput.dataset.auto === 'true') {
                usernameInput.value = username;
                usernameInput.dataset.auto = 'true';
            }
        }
    }

    firstNameInput.addEventListener('input', generateUsername);
    lastNameInput.addEventListener('input', generateUsername);

    usernameInput.addEventListener('input', function() {
        if (this.value) {
            this.dataset.auto = 'false';
        }
    });
});
</script>
@endpush
