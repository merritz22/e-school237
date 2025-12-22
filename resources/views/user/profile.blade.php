@extends('layouts.app')

@section('title', 'Profil utilisateur')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">Mon profil</h1>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 text-green-800 p-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')

        <!-- Nom -->
        <div>
            <label for="name" class="block font-semibold mb-1">Nom complet <span class="text-red-600">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block font-semibold mb-1">Email <span class="text-red-600">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Bio -->
        <div>
            <label for="bio" class="block font-semibold mb-1">Biographie</label>
            <textarea id="bio" name="bio" rows="4" maxlength="500"
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('bio', $user->bio) }}</textarea>
            @error('bio') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Website -->
        <div>
            <label for="website" class="block font-semibold mb-1">Site Web</label>
            <input type="url" id="website" name="website" value="{{ old('website', $user->website) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('website') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Location -->
        <div>
            <label for="location" class="block font-semibold mb-1">Localisation</label>
            <input type="text" id="location" name="location" value="{{ old('location', $user->location) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('location') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Avatar -->
        <div>
            <label for="avatar" class="block font-semibold mb-1">Avatar</label>
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full mb-2 object-cover">
            @endif
            <input type="file" id="avatar" name="avatar" accept="image/*"
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                          file:rounded file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700
                          hover:file:bg-indigo-100">
            @error('avatar') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-indigo-600 text-white rounded px-6 py-2 hover:bg-indigo-700 font-semibold">Mettre à jour</button>
    </form>

    <!-- Changer mot de passe -->
    <div class="mt-10 bg-white p-6 rounded-lg shadow max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-4">Changer le mot de passe</h2>

        <form action="{{ route('user.password.update') }}" method="POST" id="passwordForm" class="space-y-4">
            @csrf
            @method('PUT')

            @if($errors->has('current_password'))
                <p class="text-red-600 text-sm mb-2">{{ $errors->first('current_password') }}</p>
            @endif

            <div>
                <label for="current_password" class="block font-semibold mb-1">Mot de passe actuel <span class="text-red-600">*</span></label>
                <input type="password" id="current_password" name="current_password" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="password" class="block font-semibold mb-1">Nouveau mot de passe <span class="text-red-600">*</span></label>
                <input type="password" id="password" name="password" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label for="password_confirmation" class="block font-semibold mb-1">Confirmer le nouveau mot de passe <span class="text-red-600">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="bg-indigo-600 text-white rounded px-6 py-2 hover:bg-indigo-700 font-semibold">Mettre à jour le mot de passe</button>
        </form>
    </div>
</div>
@endsection
