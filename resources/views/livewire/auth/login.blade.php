

<div class="flex justify-center items-center h-screen">
    <form wire:submit="login" class="w-lg">
        @error('credentials')
            <div class="mt-4 font-medium px-3 py-2 rounded my-3 bg-red-100 text-red-800">
                {{ $message }}
            </div>
        @enderror
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">Connexion</flux:heading>
                <flux:text class="mt-2">Bienvenue !</flux:text>
            </div>
        
                <div class="space-y-6">
                    <flux:input icon="at-symbol" label="Email"
                     type="email" 
                     placeholder="Votre adresse mail" 
                     wire:model="email"
                     />
            
                    <flux:field>
                        <div class="mb-3 flex justify-between">
                            <flux:label>Mot de passe</flux:label>
            
                            <flux:link href="{{ Route('password.request') }}" variant="subtle" class="text-sm">Mot de passe oublié ?</flux:link>
                        </div>
            
                        <flux:input icon="lock-closed" type="password" wire:model="password" placeholder="Votre mot de passe" />
            
                        <flux:error name="password" />
                    </flux:field>
                </div>
            
                <div class="space-y-3">
                    <flux:button variant="primary" type="submit" class="w-full">Connexion</flux:button>
            
                    <flux:button href="{{ Route('register') }}" icon:trailing="arrow-up-right" class="w-full">Créer un compte</flux:button>
                </div>
        </flux:card>
    </form>
</div>