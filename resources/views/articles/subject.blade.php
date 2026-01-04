@extends('layouts.app')

@section('title', "Sujets matière : $subject->name")

@section('content')
<div class="container">
    <h1>Sujets - Matière : {{ $subject->name }}</h1>

    @if($articles->count())
        <div class="list-group">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article) }}" class="list-group-item list-group-item-action mb-2">
                    <h5>{{ $article->title }}</h5>
                    <small>{{ $article->level }} — {{ $article->type }}</small>
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    @else
        <p>Aucun sujet trouvé pour cette matière.</p>
    @endif
</div>
@endsection
