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
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Niveau</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Date de début</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Date de fin</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->level->name }}</span>
                        </td>
                        <td class="px-4 py-2">{{ $subscription->starts_at ? $subscription->starts_at->format('d/m/Y') : 'Jamais' }}</td>
                        <td class="px-4 py-2">{{ $subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : 'Jamais' }}</td>
                        <td class="px-4 py-2">
                            @if($subscription->status === 'active')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactif</span>
                            @endif
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