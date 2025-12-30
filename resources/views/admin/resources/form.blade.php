@props(['resource' => null, 'subjects', 'levels'])

<div class="space-y-6">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Titre *</label>
        <input type="text" name="title" id="title" value="{{ old('title', $resource->title ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        @error('title')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="subject_id" class="block text-sm font-medium text-gray-700">matière *</label>
        <select name="subject_id" id="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            <option value="">Sélectionnez une matière</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ old('subject_id', $resource->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
        @error('subject_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="level_id" class="block text-sm font-medium text-gray-700">Niveau *</label>
        <select name="level_id" id="level_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            <option value="">Sélectionnez un niveau</option>
            @foreach($levels as $level)
                <option value="{{ $level->id }}" {{ old('level_id', $resource->level_id ?? '') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
            @endforeach
        </select>
        @error('level_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="resource_file" class="block text-sm font-medium text-gray-700">
            {{ $resource ? 'Nouveau fichier (laisser vide pour ne pas changer)' : 'Fichier *' }}
        </label>
        <input type="file" name="resource_file" id="resource_file" 
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
               {{ $resource ? '' : 'required' }}>
        @error('resource_file')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @if($resource)
            <p class="mt-2 text-sm text-gray-500">
                Fichier actuel : {{ $resource->file_name }} ({{ $resource->formatted_file_size }})
            </p>
        @endif
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
        <textarea name="description" id="description" rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description', $resource->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>