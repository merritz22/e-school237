@props(['subscriptions'])
<!-- Tableau -->
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-3 border-b border-gray-200">
        <h6 class="text-sm font-semibold text-gray-700">Liste des abonnements</h6>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Utilisateur</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Niveau</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Téléphone</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Montant</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Date de fin</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Statut</th>
                    <th class="px-5 py-2 text-left text-xs font-semibold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->user->name }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->level->name }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->currency}}</span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->amount }} XAF</span>
                        </td>
                        <td class="px-4 py-2">{{ $subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'Jamais' }}</td>
                        <td class="px-4 py-2">
                            @if($subscription->status === 'active')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Publish/Unpublish -->
                                <form action="{{ route('admin.subscription.publish', $subscription) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="cursor-pointer p-1 {{ $subscription->status !== 'active' ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}" 
                                            title="{{ $subscription->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                        @if($subscription->status !== 'active')
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        @else
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">Aucun abonnement trouvé</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($subscriptions->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $subscriptions->withQueryString()->links() }}
        </div>
    @endif
</div>