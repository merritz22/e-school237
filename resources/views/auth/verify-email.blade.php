<x-layouts.auth>
    <div class="flex items-center justify-center h-screen">
        <div class="text-center">
            <h1 class="text-2xl font-bold mb-4">Vérifie ton adresse email</h1>
    
            <p class="mb-4">
                Un lien de vérification a été envoyé à ton email.
            </p>

            <flux:button wire:navigate href="{{ Route('home') }}" icon:trailing="arrow-up-right" class="w-full">Continuer vers e-school237</flux:button>
            
    
            {{-- <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Renvoyer l'email
                </button>
            </form> --}}
        </div>
    </div>
</x-layouts.auth>