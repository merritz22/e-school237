<x-layouts.app>
    <div>
        <div class="mb-3">
            <flux:heading size="xl" class="mb-4">
                Bienvenue sur E-School237
            </flux:heading>

            <flux:text class="text-lg">
                La plateforme éducative qui vous accompagne vers la réussite.
                Accédez facilement à des articles pédagogiques, des sujets d’évaluation et des supports de cours conçus pour renforcer vos connaissances et améliorer vos performances scolaires.
                Apprenez à votre rythme, entraînez-vous efficacement et progressez chaque jour avec E-School237.
            </flux:text>
        </div>
        <livewire:dashboard.stats lazy/>
        <div class="px-4 sm:px-6 lg:px-8 py-3">
            <livewire:dashboard.latest-articles lazy />
            <livewire:dashboard.latest-subjects lazy />
            <livewire:dashboard.latest-supports lazy />
        </div>
    </div>
</x-layouts.app>