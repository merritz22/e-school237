

<div class="flex justify-center items-center h-screen">
    <form wire:submit="register" class="w-lg">
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">Créez votre compte</flux:heading>
                <flux:text class="mt-2">Bienvenue !</flux:text>
            </div>
        
                <div class="space-y-6">
                    
                    <flux:input label="Nom"
                     type="text" 
                     placeholder="Votre nom" 
                     wire:model="name"
                     />
                    <flux:input label="Prénom"
                     type="text" 
                     placeholder="Votre prénom" 
                     wire:model="surname"
                     />

                    <flux:input icon="at-symbol" label="Email"
                     type="email" 
                     placeholder="Votre adresse mail" 
                     wire:model="email"
                     />
                     
                    <flux:input icon="lock-closed" label="Mot de passe"
                     type="password" 
                     placeholder="Votre mot de passe (min. 8 caractères)"
                     wire:model="password" />
                     
                    <flux:input icon="lock-closed" label="Confirmer le mot de passe"
                     type="password" 
                     placeholder="Confirmer votre mot de passe"
                     wire:model="confirm_password" />

                     <flux:checkbox variant="inline" label="J'accepte les conditions d'utilisation et la politique de confidentialité" wire:model="terms" />
                </div>
            
                <div class="space-y-3">
                    <flux:button variant="primary" type="submit" class="w-full">Créer mon compte</flux:button>
            
                    <flux:button wire:navigate href="{{ Route('login') }}" icon:trailing="arrow-up-right" class="w-full">Déjà membre ?</flux:button>
                </div>
        </flux:card>
    </form>
</div>