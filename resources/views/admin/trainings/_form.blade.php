@php
    $training = $training ?? null;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Titre de la formation <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title', $training?->title) }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                   placeholder="Ex : Initiation à l'Intelligence Artificielle">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description <span class="text-red-500">*</span>
            </label>
            <textarea id="description"
                      name="description"
                      rows="6"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                      placeholder="Présentez le contenu et les objectifs de la formation">{{ old('description', $training?->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Prérequis -->
        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-medium text-gray-900">Prérequis</h3>

            <div>
                <label for="technical_prerequisites" class="block text-sm font-medium text-gray-700 mb-2">
                    Prérequis techniques
                </label>
                <textarea id="technical_prerequisites"
                          name="technical_prerequisites"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('technical_prerequisites') border-red-500 @enderror"
                          placeholder="Ex : Disposer d'un ordinateur et d'une connexion internet">{{ old('technical_prerequisites', $training?->technical_prerequisites) }}</textarea>
                @error('technical_prerequisites')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="intellectual_prerequisites" class="block text-sm font-medium text-gray-700 mb-2">
                    Prérequis intellectuels
                </label>
                <textarea id="intellectual_prerequisites"
                          name="intellectual_prerequisites"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('intellectual_prerequisites') border-red-500 @enderror"
                          placeholder="Ex : Savoir lire et écrire, niveau classe de 3ème minimum">{{ old('intellectual_prerequisites', $training?->intellectual_prerequisites) }}</textarea>
                @error('intellectual_prerequisites')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Bannière -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Bannière</h3>

            @if($training?->banner)
                <img src="{{ $training->banner_url }}" alt="{{ $training->title }}" class="w-full h-40 object-cover rounded-lg mb-4">
            @endif

            <input type="file"
                   id="banner"
                   name="banner"
                   accept="image/*"
                   class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP jusqu'à 2MB. {{ $training?->banner ? 'Laissez vide pour conserver la bannière actuelle.' : '' }}</p>
            @error('banner')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Publication -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Publication</h3>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Statut <span class="text-red-500">*</span>
                </label>
                <select id="status"
                        name="status"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                    <option value="draft" @if(old('status', $training?->status ?? 'draft') === 'draft') selected @endif>Brouillon</option>
                    <option value="published" @if(old('status', $training?->status) === 'published') selected @endif>Publié</option>
                    <option value="disabled" @if(old('status', $training?->status) === 'disabled') selected @endif>Désactivé</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Durée -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Durée</h3>
            <input type="text"
                   id="duration"
                   name="duration"
                   value="{{ old('duration', $training?->duration) }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('duration') border-red-500 @enderror"
                   placeholder="Ex : 6 semaines">
            @error('duration')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Prix -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-medium text-gray-900">Tarification (FCFA)</h3>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                    Prix <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="price"
                       name="price"
                       min="0"
                       value="{{ old('price', $training?->price) }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="original_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Prix barré (optionnel)
                </label>
                <input type="number"
                       id="original_price"
                       name="original_price"
                       min="0"
                       value="{{ old('original_price', $training?->original_price) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('original_price') border-red-500 @enderror"
                       placeholder="Doit être supérieur au prix">
                @error('original_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
