@extends('layouts.admin')

@section('title', 'Gestion des abonnements')

@section('content')
<div class="bg-white p-5">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des abonnements</h1>
                <p class="text-gray-600 mt-1">Gérez tous les abonnements depuis cette interface</p>
            </div>
        </div>
    </div>

    <!--e liste des abonnements -->
    
    @component('components.adminsubscription', ['subscriptions' =>$subscriptions])
    @endcomponent
</div>

@endsection