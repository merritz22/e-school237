
<div class="flex justify-center items-center h-screen">
    <form wire:submit="send" class="w-lg">
        @if(!empty($statusMessage))
            <div class="mt-4 font-medium px-3 py-2 rounded my-3
                {{ $statusType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $statusMessage }}
            </div>
        @endif
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">Réinitialiser votre mot de passe</flux:heading>
                <flux:text class="mt-2">Entrez votre adresse email et nous vous enverrons un lien de réinitialisation !</flux:text>
            </div>
        
                <div class="space-y-6">
                    <flux:input icon="at-symbol" label="Email"
                     type="email" 
                     placeholder="Votre adresse mail" 
                     wire:model="email"
                     />
                </div>
            
                <div class="space-y-3">
                    <flux:button variant="primary" type="submit" class="w-full">Connexion</flux:button>
            
                    <flux:button wire:navigate href="{{ Route('login') }}" icon="arrow-left" class="w-full">Retour à la connexion</flux:button>
                </div>
        </flux:card>
    </form>
</div>