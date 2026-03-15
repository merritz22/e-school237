<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\User;

new class extends Component
{
    public User $user;
    public ?string $adminWhatsapp = null;

    #[Validate('nullable|regex:/^6[0-9]{8}$/', message: 'Numéro camerounais invalide (ex: 6XXXXXXXX).')]
    public string $whatsapp = '';

    public function mount(): void
    {
        $this->whatsapp = $this->user->whatsapp ?? '';

        // Récupérer le whatsapp de l'admin
        $this->adminWhatsapp = User::where('role', 'admin')
            ->where('email', 'contact@e-school237.com')
            ->value('whatsapp');
    }

    #[On('save-profile')]
    public function save(): void
    {
        $this->validate();
        $this->user->update(['whatsapp' => $this->whatsapp]);
    }
};
?>

<div>
    <div class="flex items-center gap-2 font-medium text-sm">
        <flux:icon name="phone" class="w-4 h-4 text-zinc-400" />
        {{ __('app.profile.sections.contact') }}
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Champ WhatsApp --}}
        <div>
            <flux:input wire:model="whatsapp"
                        label="WhatsApp"
                        placeholder="6XX XXX XXX"
                        icon="chat-bubble-left-ellipsis" />
            <flux:error name="whatsapp" />
        </div>

        {{-- Liens contact --}}
        <div class="flex flex-col gap-3 justify-center">

            {{-- WhatsApp admin --}}
            @if($adminWhatsapp)
                <a href="https://wa.me/237{{ $adminWhatsapp }}"
                   target="_blank"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg
                       border border-zinc-200 dark:border-zinc-700
                       hover:border-green-400 hover:bg-green-50
                       dark:hover:border-green-700 dark:hover:bg-green-900/20
                       transition-all duration-200 group">
                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30
                        flex items-center justify-center shrink-0
                        group-hover:bg-green-200 dark:group-hover:bg-green-900/50
                        transition-colors">
                        <svg class="w-4 h-4 text-green-600" fill="currentColor"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="#25d366" d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0z"/>
                            <path fill="#fff" d="M123 393l14-65a138 138 0 1150 47z"/>
                            <path fill="#25d366" d="M308 273c-3-2-6-3-9 1l-12 16c-3 2-5 3-9 1-15-8-36-17-54-47-1-4 1-6 3-8l9-14c2-2 1-4 0-6l-12-29c-3-8-6-7-9-7h-8c-2 0-6 1-10 5-22 22-13 53 3 73 3 4 23 40 66 59 32 14 39 12 48 10 11-1 22-10 27-19 1-3 6-16 2-18"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <flux:text size="sm" class="font-medium text-zinc-700 dark:text-zinc-300">
                            {{ __('app.profile.contact.whatsapp_admin') }}
                        </flux:text>
                        <flux:text size="xs" class="text-zinc-400 truncate">
                            +237 {{ $adminWhatsapp }}
                        </flux:text>
                    </div>
                    <flux:icon name="arrow-up-right"
                        class="w-4 h-4 text-zinc-400 ml-auto shrink-0
                            group-hover:text-green-500 transition-colors" />
                </a>
            @endif

            {{-- Mail suggestion développeurs --}}
            <a href="mailto:admin@e-school237.com?subject=Suggestion - E-School237 - {{$user->name}}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg
                   border border-zinc-200 dark:border-zinc-700
                   hover:border-blue-400 hover:bg-blue-50
                   dark:hover:border-blue-700 dark:hover:bg-blue-900/20
                   transition-all duration-200 group">
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30
                    flex items-center justify-center shrink-0
                    group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50
                    transition-colors">
                    <flux:icon name="envelope"
                        class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="min-w-0">
                    <flux:text size="sm" class="font-medium text-zinc-700 dark:text-zinc-300">
                        {{ __('app.profile.contact.suggest_developers') }}
                    </flux:text>
                    <flux:text size="xs" class="text-zinc-400 truncate">
                        contact@e-school237.com
                    </flux:text>
                </div>
                <flux:icon name="arrow-up-right"
                    class="w-4 h-4 text-zinc-400 ml-auto shrink-0
                        group-hover:text-blue-500 transition-colors" />
            </a>

        </div>
    </div>
</div>