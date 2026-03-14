<div class="flex justify-center items-center min-h-screen px-4">
    <div class="w-full max-w-md">

        {{-- Logo centré --}}
        <div class="text-center mb-8">
            <img src="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
                 alt="{{ config('app.name') }}"
                 class="h-14 w-14 rounded-xl object-cover mx-auto shadow-md" />
            <flux:heading size="xl" class="mt-3">{{ config('app.name') }}</flux:heading>
            <flux:text class="text-zinc-500 mt-1">{{ __('app.auth.login.subtitle') }}</flux:text>
        </div>

        <form wire:submit="login">

            {{-- Erreur credentials --}}
            @error('credentials')
                <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20
                    border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 text-sm">
                    <flux:icon name="exclamation-circle" class="w-4 h-4 shrink-0" />
                    {{ $message }}
                </div>
            @enderror

            <flux:card class="space-y-5 shadow-sm">

                <div>
                    <flux:heading size="lg">{{ __('app.auth.login.title') }}</flux:heading>
                    <flux:text class="mt-1 text-zinc-500">{{ __('app.auth.login.welcome') }}</flux:text>
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

                {{-- Mot de passe --}}
                <flux:field>
                    <div class="mb-2 flex justify-between items-center">
                        <flux:label>{{ __('app.auth.fields.password') }}</flux:label>
                        <flux:link
                            href="{{ route('password.request') }}"
                            variant="subtle"
                            class="text-sm"
                        >
                            {{ __('app.auth.login.forgot_password') }}
                        </flux:link>
                    </div>

                    <flux:input
                        icon="lock-closed"
                        type="password"
                        wire:model="password"
                        placeholder="{{ __('app.auth.fields.password_placeholder') }}"
                        viewable
                    />

                    <flux:error name="password" />
                </flux:field>

                {{-- Se souvenir de moi --}}
                <flux:checkbox
                    wire:model="remember"
                    label="{{ __('app.auth.login.remember_me') }}"
                />

                <flux:separator />

                {{-- Actions --}}
                <div class="space-y-3">
                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="login"
                    >
                        <span wire:loading.remove wire:target="login">
                            {{ __('app.auth.login.submit') }}
                        </span>
                        <span wire:loading wire:target="login" class="flex items-center gap-2">
                            <flux:icon name="arrow-path" class="w-4 h-4 animate-spin" />
                            {{ __('app.auth.login.loading') }}
                        </span>
                    </flux:button>

                    <flux:button
                        href="{{ route('register') }}"
                        icon:trailing="arrow-up-right"
                        class="w-full"
                    >
                        {{ __('app.auth.login.create_account') }}
                    </flux:button>
                </div>

            </flux:card>
        </form>

    </div>
</div>