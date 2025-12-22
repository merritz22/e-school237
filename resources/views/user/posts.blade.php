@extends('layouts.app')

@section('title', 'Mes posts')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">Mes posts</h1>

    <!-- Stats -->
    <div class="mb-8 flex space-x-6">
        <div class="bg-white p-6 rounded-lg shadow text-center flex-1">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['total'] }}</div>
            <div class="mt-2 text-gray-600">Total posts</div>
        </div>
        {{-- Uncomment if you enable published/draft stats --}}
        {{-- 
        <div class="bg-white p-6 rounded-lg shadow text-center flex-1">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['published'] }}</div>
            <div class="mt-2 text-gray-600">Publiés</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center flex-1">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['drafts'] }}</div>
            <div class="mt-2 text-gray-600">Brouillons</div>
        </div>
        --}}
        <div class="bg-white p-6 rounded-lg shadow text-center flex-1">
            <div class="text-indigo-600 text-3xl font-semibold">{{ $stats['total_likes'] }}</div>
            <div class="mt-2 text-gray-600">Likes totaux</div>
        </div>
    </div>

    <!-- Posts list -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publié</th> --}}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $post)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $post->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap">{{ $post->is_published ? 'Oui' : 'Non' }}</td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">Aucun post trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection
