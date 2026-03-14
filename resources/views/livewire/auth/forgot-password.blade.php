<div class="flex justify-center items-center min-h-screen px-4">
    <div class="w-full max-w-md">

        {{-- Logo centré --}}
        <div class="text-center mb-8">
            <img src="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
                 alt="{{ config('app.name') }}"
                 class="h-14 w-14 rounded-xl object-cover mx-auto shadow-md" />
            <flux:heading size="xl" class="mt-3">{{ config('app.name') }}</flux:heading>
            <flux:text class="text-zinc-500 mt-1">{{ __('app.auth.forgot.subtitle') }}</flux:text>
        </div>

        <form wire:submit="send">

            {{-- Message statut --}}
            @if(!empty($statusMessage))
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg text-sm border
                    {{ $statusType === 'success'
                        ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-400'
                        : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-400' }}">
                    <flux:icon
                        name="{{ $statusType === 'success' ? 'check-circle' : 'exclamation-circle' }}"
                        class="w-4 h-4 shrink-0"
                    />
                    {{ $statusMessage }}
                </div>
            @endif

            <flux:card class="space-y-5 shadow-sm">

                <div>
                    <flux:heading size="lg">{{ __('app.auth.forgot.title') }}</flux:heading>
                    <flux:text class="mt-1 text-zinc-500">{{ __('app.auth.forgot.description') }}</flux:text>
                </div>

                <flux:separator />

                {{-- Email --}}
                <flux:input
                    icon="at-symbol"
                    label="{{ __('app.auth.fields.email') }}"
                    type="email"
                    placeholder="{{ __('app.auth.fields.email_placeholder') }}"
                    wire:model="email"
                    autofocus
                />

                <flux:separator />

                {{-- Actions --}}
                <div class="space-y-3">
                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="send"
                    >
                        <span wire:loading.remove wire:target="send">
                            {{ __('app.auth.forgot.submit') }}
                        </span>
                        <span wire:loading wire:target="send" class="flex items-center gap-2">
                            <flux:icon name="arrow-path" class="w-4 h-4 animate-spin" />
                            {{ __('app.auth.forgot.loading') }}
                        </span>
                    </flux:button>

                    <flux:button
                        wire:navigate
                        href="{{ route('login') }}"
                        icon="arrow-left"
                        class="w-full"
                    >
                        {{ __('app.auth.forgot.back_to_login') }}
                    </flux:button>
                </div>

            </flux:card>
        </form>

    </div>
</div>