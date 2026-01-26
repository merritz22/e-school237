@props(['topic'=> $topic])

<div class="space-y-6">
    <!-- Title -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Titre de la matière <span class="text-red-500">*</span>
        </label>
        <input type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $topic->name ?? '') }}"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                placeholder="Entrez le titre de votre matière">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Description de la matière
        </label>
        <textarea type="text" 
                id="description" 
                name="description" 
                value="{{ old('description') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                placeholder="Entrez la description de votre matière"
            cols="30" rows="5">{{$topic->description ?? ''}}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>