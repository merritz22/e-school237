@extends('layouts.app')

@section('title', $resource->title)

@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $resource->title }}</h1>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <span class="mr-4">Posté par {{ $resource->uploader->name }}</span>
                            <span>{{ $resource->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold 
                              {{ $resource->is_free ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $resource->is_free ? 'Gratuit' : 'Premium' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-2 text-sm text-gray-500 px-5">
                <p><span class="font-medium">Niveau :</span> {{ $resource->level->name ?? $resource->level_id }}</p>
                <p><span class="font-medium">Matière :</span> {{ $resource->subject->name ?? 'N/A' }}</p>
                <p>.</p>
                {{-- <p><span class="font-medium">Type :</span> {{ $resource->type }}</p> --}}
            </div>

            <div class="p-6">
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $resource->description }}</p>
                </div>

                {{-- <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($resource->isImage())
                                <img class="h-12 w-12 object-cover rounded" src="{{ $resource->getFileUrl() }}" alt="">
                            @else
                                <div class="h-12 w-12 rounded bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold">{{ strtoupper($resource->file_extension) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $resource->file_name }}</div>
                            <div class="text-sm text-gray-500">{{ $resource->formatted_file_size }}</div>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('resources.download', $resource) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Télécharger
                            </a>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="p-0 overflow-hidden">
                <div class="relative w-full h-100 mb-4 overflow-hidden bg-none">
                    <iframe 
                        src="{{ url('/support/pdf/' . $resource->id) }}
                            #page=1
                            &view=FitH
                            &scrollbar=0
                            &toolbar=0
                            &navpanes=0"
                        class="w-full h-full"
                        loading="lazy">
                    </iframe>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="text-blue-600/20 text-base md:text-9xl font-bold select-none">
                            E-School237
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ressources similaires</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($relatedResources as $related)
                    <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
                        <a href="{{ route('resources.show', $related) }}" class="block">
                            <h4 class="font-medium text-gray-900">{{ $related->title }}</h4>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span>{{ $related->category->name ?? '' }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $related->downloads_count }} téléchargements</span>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection