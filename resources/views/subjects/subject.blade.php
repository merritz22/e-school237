@extends('layouts.app')

@section('title', "Sujets matière : $subject_name")

@section('content')
<div class="container">
    <h1>Sujets - Matière : {{ $subject_name }}</h1>

    @if($subjects->count())
        <div class="list-group">
            @foreach($subjects as $subject)
                <a href="{{ route('subjects.show', $subject) }}" class="list-group-item list-group-item-action mb-2">
                    <h5>{{ $subject->title }}</h5>
                    <small>{{ $subject->level }} — {{ $subject->type }}</small>
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $subjects->links() }}
        </div>
    @else
        <p>Aucun sujet trouvé pour cette matière.</p>
    @endif
</div>
@endsection
