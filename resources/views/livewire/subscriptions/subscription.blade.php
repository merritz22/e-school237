<div class="mt-5">
    <form wire:submit="store">
        <flux:card class="space-y-3">

            <h2 class="text-xl font-bold">
                Créer un abonnement
            </h2>
            <!-- Niveau -->
            <flux:select
                wire:model="level"
                description="La classe pour laquelle vous souhaitez vous abonnez"
                label="Niveau"
                placeholder="Choisir un niveau"
            >
                <option value="">Choisir un niveau</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}">
                        {{ $level->name }}
                    </option>
                @endforeach
            </flux:select>

            <!-- Prix -->
            <flux:select
                wire:model="price"
                label="Prix"
                placeholder="Choisir un abonement"
                description="Le type d'abonnement"
            >
                <option value="">Choisir un abonnement</option>
                <option value="3000">Abonnement classique (3 000 XAF)</option>
                <option value="6000">Abonnement excellence (6 000 XAF)</option>
                <option value="8000">Abonnement premium (8 000 XAF)</option>
            </flux:select>

            <!-- Téléphone -->
            <flux:input icon="phone"
                wire:model="phone"
                description="votre whatsapp pour garantir votre suivi"
                label="Téléphone"
                placeholder="Ex: 6XXXXXXXX"
            />

            <flux:button
                type="submit"
                variant="primary"
                class="mt-5"
            >
                Créer l'abonnement
            </flux:button>
        </flux:card>
        
    </form>
    <flux:modal :dismissible="false" wire:close wire:model="showModal" title="Abonnement créé" size="sm" centered>
        <div class="text-center space-y-4">
            <flux:icon.bolt variant="solid" class="text-green-500 dark:text-green-300 mx-auto" />
            <p>Un mail avec les instructions a été envoyé. Vérifiez votre boîte de réception.</p>
            <flux:button variant="primary" wire:click="closeModal">Fermer</flux:button>
        </div>
    </flux:modal>
</div>