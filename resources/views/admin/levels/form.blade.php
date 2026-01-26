@props(['level'=> $level, 'systems'=> $systems, 'schools'=> $schools])

<div class="space-y-6">
    <!-- Title -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Nom de la classe <span class="text-red-500">*</span>
        </label>
        <input type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $level->name ?? '') }}"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                placeholder="Entrez le nom de votre classe">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <!-- System Filter -->
    <div>
        <label for="system" class="block text-sm font-medium text-gray-700 mb-2">Système scolaire <span class="text-red-500">*</span></label>
        <select id="system" 
                name="system" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Tous les systèmes</option>
            @foreach($systems as $system)
                <option value="{{ $system }}" 
                        @if(old('system', $level->system ?? '') == $system) selected @endif>
                    {{ $system }}
                </option>
            @endforeach
        </select>
        @error('system')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- School Filter -->
    <div>
        <label for="school" class="block text-sm font-medium text-gray-700 mb-2">Type d'école <span class="text-red-500">*</span></label>
        <select id="school" 
                name="school" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Toutes les écoles</option>
            @foreach($schools as $school)
                <option value="{{ $school }}" 
                        @if(old('school', $level->school ?? '') == $school) selected @endif>
                    {{ $school }}
                </option>
            @endforeach
        </select>
        @error('school')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Description de la classe
        </label>
        <textarea type="text" 
                id="description" 
                name="description" 
                value="{{ old('description') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                placeholder="Entrez la description de votre classe"
            cols="30" rows="5">{{$level->description ?? ''}}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>