<div class="flex justify-center items-center min-h-screen px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo centré --}}
        <div class="text-center mb-8">
            <img src="{{ Vite::asset('resources/images/e-school-logo.jpg') }}"
                 alt="{{ config('app.name') }}"
                 class="h-14 w-14 rounded-xl object-cover mx-auto shadow-md" />
            <flux:heading size="xl" class="mt-3">{{ config('app.name') }}</flux:heading>
            <flux:text class="text-zinc-500 mt-1">{{ __('app.auth.register.subtitle') }}</flux:text>
        </div>

        <form wire:submit="register">
            <flux:card class="space-y-5 shadow-sm">

                <div>
                    <flux:heading size="lg">{{ __('app.auth.register.title') }}</flux:heading>
                    <flux:text class="mt-1 text-zinc-500">{{ __('app.auth.register.welcome') }}</flux:text>
                </div>

                <flux:separator />

                {{-- Nom & Prénom --}}
                <div class="grid grid-cols-2 gap-4">
                    <flux:input
                        icon="user"
                        label="{{ __('app.auth.fields.last_name') }}"
                        type="text"
                        placeholder="{{ __('app.auth.fields.last_name_placeholder') }}"
                        wire:model="name"
                    />
                    <flux:input
                        icon="user"
                        label="{{ __('app.auth.fields.first_name') }}"
                        type="text"
                        placeholder="{{ __('app.auth.fields.first_name_placeholder') }}"
                        wire:model="surname"
                    />
                </div>

                {{-- Email --}}
                <flux:input
                    icon="at-symbol"
                    label="{{ __('app.auth.fields.email') }}"
                    type="email"
                    placeholder="{{ __('app.auth.fields.email_placeholder') }}"
                    wire:model="email"
                />

                {{-- Mot de passe --}}
                <flux:input
                    icon="lock-closed"
                    label="{{ __('app.auth.fields.password') }}"
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

                {{-- CGU --}}
                <flux:field>
                    <div class="flex items-start gap-3">
                        <flux:checkbox wire:model="terms" id="terms" />
                        <label for="terms" class="text-sm text-zinc-600 dark:text-zinc-400 leading-snug cursor-pointer">
                            {!! __('app.auth.register.terms_html', [
                                'terms_url'   => route('terms'),
                                'privacy_url' => route('privacy'),
                            ]) !!}
                        </label>
                    </div>
                    <flux:error name="terms" />
                </flux:field>

                <flux:separator />

                {{-- Actions --}}
                <div class="space-y-3">
                    <flux:button
                        variant="primary"
                        type="submit"
                        class="w-full"
                        wire:loading.attr="disabled"
                        wire:target="register"
                    >
                        <span wire:loading.remove wire:target="register">
                            {{ __('app.auth.register.submit') }}
                        </span>
                        <span wire:loading wire:target="register" class="flex items-center gap-2">
                            <flux:icon name="arrow-path" class="w-4 h-4 animate-spin" />
                            {{ __('app.auth.register.loading') }}
                        </span>
                    </flux:button>

                    <flux:button
                        wire:navigate
                        href="{{ route('login') }}"
                        icon:trailing="arrow-right"
                        class="w-full"
                    >
                        {{ __('app.auth.register.already_member') }}
                    </flux:button>
                </div>

            </flux:card>
        </form>

    </div>
</div>