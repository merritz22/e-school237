@extends('layouts.app')

@section('title', 'Historique d\'activité')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6">Historique d'activité</h1>

    @if($activities->isEmpty())
        <p class="text-gray-500">Aucune activité récente.</p>
    @else
        <ul class="space-y-4">
            @foreach($activities as $activity)
                <li class="bg-white rounded-lg shadow p-4 flex items-center space-x-4">
                    @if($activity['icon'] === 'download')
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v12m0 0l-4-4m4 4l4-4"/></svg>
                    @elseif($activity['icon'] === 'edit')
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/></svg>
                    @endif

                    <div>
                        <p class="font-semibold">
                            {{ $activity['action'] }} {{ $activity['item'] }}
                        </p>
                        <p class="text-gray-500 text-sm">{{ $activity['date']->format('d/m/Y H:i') }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
