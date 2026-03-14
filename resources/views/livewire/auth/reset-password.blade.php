<div class="flex justify-center items-center min-h-screen px-4">
    <div class="w-full max-w-md">

        {{-- Logo centré --}}
        <div class="text-center mb-8">
            <img src="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
                 alt="{{ config('app.name') }}"
                 class="h-14 w-14 rounded-xl object-cover mx-auto shadow-md" />
            <flux:heading size="xl" class="mt-3">{{ config('app.name') }}</flux:heading>
            <flux:text class="text-zinc-500 mt-1">{{ __('app.auth.reset.subtitle') }}</flux:text>
        </div>

        <form wire:submit.prevent="resetPassword">

            {{-- Erreur credentials --}}
            @error('credentials')
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg text-sm
                    bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
                    text-red-700 dark:text-red-400">
                    <flux:icon name="exclamation-circle" class="w-4 h-4 shrink-0" />
                    {{ $message }}
                </div>
            @enderror

            <flux:card class="space-y-5 shadow-sm">

                <div>
                    <flux:heading size="lg">{{ __('app.auth.reset.title') }}</flux:heading>
                    <flux:text class="mt-1 text-zinc-500">{{ __('app.auth.reset.description') }}</flux:text>
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

                {{-- Nouveau mot de passe --}}
                <flux:input
                    icon="lock-closed"
                    label="{{ __('app.auth.reset.new_password') }}"
                    type="password"
                    placeholder="{{ __('app.auth.fields.password_placeholder') }}"
                    wire:model="password"
                    viewable
                />

                {{-- Confirmation --}}
                <flux:input
                    icon="lock-closed"
                    label="{{ __('app.auth.fields.confirm_password') }}"
                    type="password"
                    placeholder="{{ __('app.auth.fields.confirm_password_placeholder') }}"
                    wire:model="confirm_password"
                    viewable
                />

                <flux:separator />

                {{-- Actions --}}
                <div class="space-y-3">
                    <flux:button
                        type="submit"
                        variant="primary"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="resetPassword"
                    >
                        <span wire:loading.remove wire:target="resetPassword">
                            {{ __('app.auth.reset.submit') }}
                        </span>
                        <span wire:loading wire:target="resetPassword" class="flex items-center gap-2">
                            <flux:icon name="arrow-path" class="w-4 h-4 animate-spin" />
                            {{ __('app.auth.reset.loading') }}
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