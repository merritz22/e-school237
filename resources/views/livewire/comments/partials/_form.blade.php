@auth
    <div
        x-data="{ focused: false }"
        :class="focused
            ? 'ring-2 ring-primary/30 shadow-lg shadow-primary/5'
            : 'ring-1 ring-zinc-200 dark:ring-zinc-700 shadow-sm'"
        class="bg-white dark:bg-zinc-900 rounded-2xl p-4 transition-all duration-300"
    >
        <div class="flex gap-3">

            {{-- Avatar --}}
            <div class="shrink-0 mt-0.5">
                @if(auth()->user()->avatar_url)
                    <img src="{{ asset('storage/' . auth()->user()->avatar_url) }}"
                         class="w-9 h-9 rounded-full object-cover ring-2 ring-primary/20" />
                @else
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-primary/60
                                flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="flex-1 space-y-3 relative">

                {{-- Textarea --}}
                <textarea
                    x-model="$wire.body"
                    x-on:focus="focused = true"
                    x-on:blur="focused = false"
                    x-on:input.debounce.300ms="
                        const match = $el.value.match(/@(\w*)$/);
                        if (match && match[1].length >= 2) $wire.searchMentions(match[1]);
                        else $wire.set('showSuggestions', false);
                    "
                    wire:loading.attr="disabled"
                    wire:target="postComment"
                    rows="3"
                    placeholder="{{ __('Partagez votre avis... Tapez @ pour mentionner quelqu\'un') }}"
                    class="w-full bg-transparent resize-none text-sm text-zinc-800 dark:text-zinc-200
                           placeholder-zinc-400 focus:outline-none leading-relaxed
                           disabled:opacity-50 disabled:cursor-not-allowed"
                ></textarea>

                {{-- Suggestions @mention --}}
                @if($showSuggestions && count($mentionSuggestions))
                    <div class="absolute z-50 top-full left-0 mt-1 w-60 bg-white dark:bg-zinc-800
                                border border-zinc-100 dark:border-zinc-700 rounded-2xl shadow-xl overflow-hidden">
                        <div class="px-3 py-2 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                            <flux:icon name="at-symbol" class="w-3.5 h-3.5 text-zinc-400" />
                            <p class="text-xs text-zinc-400 font-medium">{{ __('Mentionner') }}</p>
                        </div>
                        @foreach($mentionSuggestions as $suggestion)
                            <button
                                wire:click="insertMention('{{ $suggestion['name'] }}')"
                                class="w-full text-left px-3 py-2.5 text-sm hover:bg-zinc-50
                                       dark:hover:bg-zinc-700/50 transition-colors flex items-center gap-2.5"
                            >
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary to-primary/60
                                            flex items-center justify-center text-white text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($suggestion['name'], 0, 1)) }}
                                </div>
                                <span class="font-medium text-zinc-700 dark:text-zinc-200">
                                    {{ $suggestion['name'] }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Footer --}}
                <div class="flex items-center justify-between pt-2 border-t border-zinc-100 dark:border-zinc-800">
                    <p class="text-xs tabular-nums">
                        <span wire:loading wire:target="postComment" class="flex items-center gap-1.5 text-primary">
                            <flux:icon name="arrow-path" class="h-3 w-3 animate-spin" />
                            {{ __('Publication...') }}
                        </span>
                        <span
                            wire:loading.remove wire:target="postComment"
                            x-data
                            x-text="
                                (() => {
                                    const len = $wire.body.length;
                                    const remaining = 2000 - len;
                                    return len === 0
                                        ? '0 / 2000'
                                        : remaining < 0
                                            ? remaining + ' / 2000'
                                            : len + ' / 2000';
                                })()
                            "
                            :class="
                                (() => {
                                    const len = $wire.body.length;
                                    if (len === 0)        return 'text-zinc-400';
                                    if (len >= 2000)      return 'text-red-500 font-semibold';
                                    if (len >= 1800)      return 'text-amber-500 font-medium';
                                    return 'text-zinc-400';
                                })()
                            "
                        ></span>
                    </p>

                    <flux:button
                        wire:click="postComment"
                        wire:loading.attr="disabled"
                        wire:target="postComment"
                        :disabled="strlen($body) < 2 || strlen($body) > 2000"
                        variant="primary"
                        size="sm"
                        icon="paper-airplane"
                        class="rounded-xl"
                    >
                        <span wire:loading.remove wire:target="postComment">{{ __('Publier') }}</span>
                        <span wire:loading wire:target="postComment">{{ __('Envoi...') }}</span>
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="flex items-center gap-4 bg-white dark:bg-zinc-900 rounded-2xl p-5
                border border-dashed border-zinc-200 dark:border-zinc-700">
        <div class="w-10 h-10 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0">
            <flux:icon name="user" class="h-5 w-5 text-zinc-400" />
        </div>
        <div>
            <p class="text-sm font-medium text-zinc-600 dark:text-zinc-300">
                {{ __('Rejoignez la discussion') }}
            </p>
            <p class="text-xs text-zinc-400 mt-0.5">
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">
                    {{ __('Connectez-vous') }}
                </a>
                {{ __(' pour laisser un commentaire.') }}
            </p>
        </div>
    </div>
@endauth