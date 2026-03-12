<div class="flex justify-center items-center h-screen">

    <form wire:submit.prevent="resetPassword" class="w-lg">
        @error('credentials')
            <div class="mt-4 font-medium px-3 py-2 rounded my-3 bg-red-100 text-red-800">
                {{ $message }}
            </div>
        @enderror

        <flux:card class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Réinitialiser votre mot de passe
                </flux:heading>

                <flux:text class="mt-2">
                    Entrez votre email et votre nouveau mot de passe
                </flux:text>
            </div>

            <flux:input icon="at-symbol" label="Email"
                type="email" 
                placeholder="Votre adresse mail" 
                wire:model="email"
                />

            <flux:input icon="lock-closed"
             label="Nouveau mot de passe"
             type="password" 
             wire:model="password" 
             placeholder="Votre mot de passe" />
            

            <flux:input icon="lock-closed"
             label="Confirmer le mot de passe"
             type="password" 
             wire:model="confirm_password" 
             placeholder="Votre mot de passe" />
            

            <flux:button
                type="submit"
                variant="primary"
                class="w-full"
            >
                Réinitialiser le mot de passe
            </flux:button>

        </flux:card>

    </form>

</div>