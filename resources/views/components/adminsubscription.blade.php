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
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Téléphone (dépôt)</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Whatsapp</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Montant</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Statut</th>
                    <th class="px-5 py-2 text-left text-xs font-semibold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->user?->name }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->level?->name }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->phone }}</span>
                        </td>
                        <td class="px-4 py-2">
                            @if($subscription->user?->whatsapp)
                                <a href="https://wa.me/{{ config('subscriptions.country_code') }}{{ $subscription->user?->whatsapp }}">
                                    {{ $subscription->user?->whatsapp }}
                                </a>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <span class="font-semibold">{{ $subscription->amount }} {{ config('subscriptions.currency') }}</span>
                        </td>
                        <td class="px-4 py-2">
                            @if($subscription->status === 'active')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Activation (à sens unique : impossible de désactiver un abonnement actif depuis ici) -->
                                @if($subscription->status !== 'active')
                                    <form action="{{ route('admin.subscription.publish', $subscription) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="cursor-pointer p-1 text-orange-600 hover:text-orange-900"
                                                title="Activer">
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-block p-1 text-green-600 cursor-default" title="Abonnement actif — non désactivable depuis cette interface">
                                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </span>
                                @endif

                                @if($subscription->status !== 'active')
                                    <form id="deleteSubscriptionForm-{{ $subscription->id }}" action="{{ route('admin.subscription.destroy', $subscription) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="openDeleteSubscriptionModal({{ $subscription->id }})"
                                                class="cursor-pointer p-1 text-red-600 hover:text-red-900"
                                                title="Supprimer">
                                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Modal suppression -->
                                    <div id="deleteSubscriptionModal-{{ $subscription->id }}"
                                        class="fixed inset-0 bg-gray-300 bg-opacity-40 hidden items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
                                            <h3 class="text-lg font-semibold mb-4">Confirmer la suppression</h3>
                                            <p class="mb-6">Supprimer l'abonnement de {{ $subscription->user?->name }} ? Cette action est irréversible.</p>
                                            <div class="flex justify-end space-x-4">
                                                <button type="button" onclick="closeDeleteSubscriptionModal({{ $subscription->id }})" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                                                <button type="button" onclick="submitDeleteSubscriptionForm({{ $subscription->id }})" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-block p-1 text-gray-300 cursor-not-allowed" title="Un abonnement actif ne peut pas être supprimé — désactivez-le d'abord">
                                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </span>
                                @endif
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

@push('scripts')
<script>
    function openDeleteSubscriptionModal(id) {
        const modal = document.getElementById('deleteSubscriptionModal-' + id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeDeleteSubscriptionModal(id) {
        const modal = document.getElementById('deleteSubscriptionModal-' + id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function submitDeleteSubscriptionForm(id) {
        const form = document.getElementById('deleteSubscriptionForm-' + id);
        if (form) {
            form.submit();
        }
    }
</script>
@endpush