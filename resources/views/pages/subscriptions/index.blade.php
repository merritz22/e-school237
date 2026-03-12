<x-layouts.app>
    <div>

        <flux:heading size="xl" class="mb-4">
            Démarrer avec un abonnement pour toute l'année scolaire.
        </flux:heading>

        <flux:text class="text-lg">
            Profitez de toutes les ressources pour progresser à votre rythme.
            Accédez librement aux contenus et résiliez à tout moment, sans engagement.
        </flux:text>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-3">

        <!-- Abonnement Classique -->
        <flux:card>
            <flux:heading size="xl">
                Abonnement classique
            </flux:heading>

            <flux:text>
                Apprends mieux, progresse plus vite et réussis ton année scolaire.
            </flux:text>

            <div class="flex justify-between font-bold mb-6">
                <span>Prix : 3 000 XAF</span>
                <span>Durée : 1 an</span>
            </div>

            <ul class="space-y-4 mb-8">
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Accès illimité aux sujets séquentiels, examens et corrigés</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Téléchargement des fiches de TD</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Annales et épreuves régionales</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Mises à jour régulières du contenu</span>
                </li>
            </ul>

            <flux:button href="{{ url('subscription/create') }}" variant="primary">
                Je m’abonne maintenant
            </flux:button>

        </flux:card>


        <!-- Abonnement Excellence -->
        <flux:card>
            <flux:heading size="xl">
                Abonnement excellence
            </flux:heading>

            <flux:text>
                Parfait pour avoir une bibliothèque complète de révision
            </flux:text>

            <div class="flex justify-between font-bold mb-6">
                <span>Prix : 8 000 XAF</span>
                <span>Durée : 1 an</span>
            </div>

            <ul class="space-y-4 mb-8">
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Tout le contenu de l'abonnement premium</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Supports pédagogiques complets</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Annales des matières</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Documents de synthèse par matière</span>
                </li>
            </ul>

            <flux:button href="{{ url('subscription/create') }}" variant="primary">
                Je m’abonne maintenant
            </flux:button>

        </flux:card>


        <!-- Abonnement Premium -->
        <flux:card>
            <flux:heading size="xl">
                Abonnement premium
            </flux:heading>

            <flux:text>
                Recommandé pour les élèves en classes d’examen
            </flux:text>

            <flux:badge color="warning" class="mb-3">
                ⭐ Le plus choisi
            </flux:badge>

            <div class="flex justify-between font-bold mb-6">
                <span>Prix : <span class="line-through">10 000</span> 6 000 XAF</span>
                <span>Durée : 1 an</span>
            </div>

            <ul class="space-y-4 mb-8">
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Accès illimité aux sujets et corrigés</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Épreuves d’Afrique francophone</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Fiches TD et corrigés détaillés</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Annales et épreuves régionales</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Groupe WhatsApp privé avec suivi</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Vidéos explicatives</span>
                </li>
                <li class="flex items-start gap-3">
                    <flux:icon name="check-circle"></flux:icon>
                    <span>Conseils méthodologiques d’examen</span>
                </li>
            </ul>

            <flux:button href="{{ url('subscription/create') }}" variant="primary">
                Commencer mon abonnement
            </flux:button>

        </flux:card>

    </div>
</x-layouts.app>