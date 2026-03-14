<div class="mt-5 max-w-xl mx-auto">
    <form wire:submit="store">

        {{-- ===== CARD PRINCIPALE ===== --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800
                bg-gradient-to-r from-sky-50 to-blue-50
                dark:from-sky-900/10 dark:to-blue-900/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500 flex items-center justify-center shadow-sm">
                        <flux:icon name="banknotes" class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <flux:heading size="lg" class="font-bold">{{ __('app.subscriptions.create.title') }}</flux:heading>
                        <flux:text size="sm" class="text-zinc-400">{{ __('app.subscriptions.create.subtitle') }}</flux:text>
                    </div>
                </div>
            </div>

            {{-- Champs --}}
            <div class="px-6 py-5 space-y-5">

                {{-- Niveau --}}
                <flux:select
                    wire:model="level"
                    label="{{ __('app.subscriptions.create.level.label') }}"
                    placeholder="{{ __('app.subscriptions.create.level.placeholder') }}"
                    description="{{ __('app.subscriptions.create.level.description') }}"
                >
                    <option value="">{{ __('app.subscriptions.create.level.placeholder') }}</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </flux:select>

                {{-- Plans --}}
                <div class="space-y-2">
                    <flux:label>{{ __('app.subscriptions.create.plan.label') }}</flux:label>
                    <div class="grid gap-3">

                        {{-- Classique --}}
                        <label class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-150
                            {{ $price == '3000'
                                ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/10'
                                : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                            <input type="radio" wire:model.live="price" value="3000" class="sr-only" />
                            <div class="w-9 h-9 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0">
                                <flux:icon name="star" class="w-5 h-5 text-zinc-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ __('app.subscriptions.create.plan.classic.name') }}</p>
                                <p class="text-xs text-zinc-400">{{ __('app.subscriptions.create.plan.classic.description') }}</p>
                            </div>
                            <span class="text-sm font-bold text-zinc-700 dark:text-zinc-200 shrink-0">{{ __('app.subscriptions.create.plan.classic.price') }}</span>
                            @if($price == '3000')
                                <flux:icon name="check-circle" class="w-5 h-5 text-sky-500 shrink-0" />
                            @endif
                        </label>

                        {{-- Excellence --}}
                        <label class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-150
                            {{ $price == '6000'
                                ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/10'
                                : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                            <input type="radio" wire:model.live="price" value="6000" class="sr-only" />
                            <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center shrink-0">
                                <flux:icon name="academic-cap" class="w-5 h-5 text-amber-500" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ __('app.subscriptions.create.plan.excellence.name') }}</p>
                                    <flux:badge size="sm" color="amber">{{ __('app.subscriptions.create.plan.excellence.badge') }}</flux:badge>
                                </div>
                                <p class="text-xs text-zinc-400">{{ __('app.subscriptions.create.plan.excellence.description') }}</p>
                            </div>
                            <span class="text-sm font-bold text-zinc-700 dark:text-zinc-200 shrink-0">{{ __('app.subscriptions.create.plan.excellence.price') }}</span>
                            @if($price == '6000')
                                <flux:icon name="check-circle" class="w-5 h-5 text-sky-500 shrink-0" />
                            @endif
                        </label>

                        {{-- Premium --}}
                        <label class="relative flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all duration-150
                            {{ $price == '8000'
                                ? 'border-sky-500 bg-sky-50 dark:bg-sky-900/10'
                                : 'border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                            <input type="radio" wire:model.live="price" value="8000" class="sr-only" />
                            <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center shrink-0">
                                <flux:icon name="bolt" class="w-5 h-5 text-purple-500" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-100">{{ __('app.subscriptions.create.plan.premium.name') }}</p>
                                <p class="text-xs text-zinc-400">{{ __('app.subscriptions.create.plan.premium.description') }}</p>
                            </div>
                            <span class="text-sm font-bold text-zinc-700 dark:text-zinc-200 shrink-0">{{ __('app.subscriptions.create.plan.premium.price') }}</span>
                            @if($price == '8000')
                                <flux:icon name="check-circle" class="w-5 h-5 text-sky-500 shrink-0" />
                            @endif
                        </label>

                    </div>
                    <flux:error name="price" />
                </div>

                {{-- Téléphone --}}
                <flux:input
                    icon="phone"
                    wire:model="phone"
                    label="{{ __('app.subscriptions.create.phone.label') }}"
                    placeholder="{{ __('app.subscriptions.create.phone.placeholder') }}"
                    description="{{ __('app.subscriptions.create.phone.description') }}"
                />

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800
                flex items-center justify-between gap-4">
                <flux:text size="xs" class="text-zinc-400">
                    <flux:icon name="shield-check" class="w-3.5 h-3.5 inline mr-1 text-emerald-500" />
                    {{ __('app.subscriptions.create.security') }}
                </flux:text>
                <flux:button type="submit" variant="primary" icon="arrow-right">
                    {{ __('app.subscriptions.create.submit') }}
                </flux:button>
            </div>

        </div>

    </form>

    {{-- ===== MODAL SUCCÈS ===== --}}
    <flux:modal :dismissible="false" wire:close wire:model="showModal" size="sm" centered>
        <div class="text-center space-y-5 py-4">

            <div class="w-16 h-16 rounded-2xl bg-emerald-100 dark:bg-emerald-900/20
                flex items-center justify-center mx-auto">
                <flux:icon name="bolt" variant="solid" class="w-8 h-8 text-emerald-500" />
            </div>

            <div class="space-y-1">
                <flux:heading size="lg" class="font-bold">{{ __('app.subscriptions.modal.title') }}</flux:heading>
                <flux:text class="text-zinc-500">{{ __('app.subscriptions.modal.message') }}</flux:text>
            </div>

            <flux:button variant="primary" wire:click="closeModal" class="w-full">
                {{ __('app.subscriptions.modal.close') }}
            </flux:button>

        </div>
    </flux:modal>

</div>