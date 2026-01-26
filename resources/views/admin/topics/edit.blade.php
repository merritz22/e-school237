@extends('layouts.admin')

@section('title', 'Modifier une matière')

@section('content')
<div class="bg-white p-5">
    
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Modifier une matière</h1>
            </div>
            
            <a href="{{ route('admin.topics.index') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.topics.update', $topic) }}" 
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="p-6">
            @component('admin.topics.form', ['topic' => $topic])
            @endcomponent
        </div>
        <div class="px-6 py-4 bg-none border-t border-none flex justify-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection