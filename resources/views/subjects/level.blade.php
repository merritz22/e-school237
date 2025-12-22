@extends('layouts.app')

@section('title', "Sujets niveau : $level")

@section('content')
<div class="container">
    <h1>Sujets - Niveau : {{ $level }}</h1>

    @if($subjects->count())
        <div class="list-group">
            @foreach($subjects as $subject)
                <a href="{{ route('subjects.show', $subject) }}" class="list-group-item list-group-item-action mb-2">
                    <h5>{{ $subject->title }}</h5>
                    <small>{{ $subject->subject_name }} — {{ $subject->type }}</small>
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $subjects->links() }}
        </div>
    @else
        <p>Aucun sujet trouvé pour ce niveau.</p>
    @endif
</div>
@endsection
