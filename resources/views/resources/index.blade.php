@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Ressources Éducatives</h1>
        <p class="text-gray-600">Trouvez des documents utiles pour votre formation</p>
    </div>

    <div class="mb-6">
        <form action="{{ route('resources.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="w-full md:w-48">
                <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Filtrer
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($resources as $resource)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                            <a href="{{ route('resources.show', $resource) }}" class="hover:text-blue-600">{{ $resource->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-2">{{ Str::limit($resource->description, 100) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ strtoupper($resource->file_extension) }}
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-sm text-gray-500">{{ $resource->formatted_file_size }}</span>
                    <span class="text-sm text-gray-500">{{ $resource->downloads_count }} téléchargements</span>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                <span class="text-sm text-gray-600">{{ $resource->category->name }}</span>
                <a href="{{ route('resources.download', $resource) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    Télécharger
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Aucune ressource trouvée</h3>
            <p class="mt-1 text-gray-500">Essayez de modifier vos critères de recherche.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $resources->links() }}
    </div>
</div>
@endsection