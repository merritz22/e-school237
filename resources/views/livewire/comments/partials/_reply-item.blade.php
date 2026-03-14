<div
    wire:key="reply-{{ $reply->id }}"
    class="flex gap-2.5 group/reply"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-x-2"
    x-transition:enter-end="opacity-100 translate-x-0"
>
    {{-- Avatar --}}
    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-zinc-400 to-zinc-500
                flex items-center justify-center text-white text-xs font-bold shrink-0 mt-0.5">
        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
    </div>

    <div class="flex-1">
        {{-- Bulle --}}
        <div class="bg-zinc-50 dark:bg-zinc-800/60 rounded-2xl rounded-tl-sm
                    border border-zinc-100 dark:border-zinc-800">

            {{-- Corps --}}
            <div class="px-3 pt-2.5 pb-2">
                <div class="flex items-start justify-between mb-1.5">
                    <div class="flex items-center gap-1.5">
                        <span class="font-semibold text-xs text-zinc-700 dark:text-zinc-200">
                            {{ $reply->user->name }}
                        </span>
                        @if($reply->user_id === auth()->id())
                            <span class="text-xs bg-primary/10 text-primary px-1.5 py-0.5 rounded-full">
                                {{ __('Vous') }}
                            </span>
                        @endif
                    </div>
                    <span class="text-xs text-zinc-400 tabular-nums">
                        {{ $reply->created_at->diffForHumans() }}
                    </span>
                </div>

                @if($reply->is_deleted)
                    <p class="text-xs text-zinc-400 italic flex items-center gap-1">
                        <flux:icon name="trash" class="h-3 w-3" />
                        {{ __('Réponse supprimée.') }}
                    </p>
                @else
                    <p class="text-xs text-zinc-600 dark:text-zinc-300 leading-relaxed">
                        {!! $this->renderMentions($reply->body) !!}
                    </p>
                @endif
            </div>

            {{-- Séparateur + Footer actions --}}
            @if(!$reply->is_deleted)
                <div class="border-t border-zinc-100 dark:border-zinc-800 px-2 py-1 flex items-center justify-between">

                    {{-- Gauche : Supprimer --}}
                    <div>
                        @if(auth()->id() === $reply->user_id || auth()->user()?->role === 'admin')
                            <button
                                wire:click="deleteComment({{ $reply->id }})"
                                wire:confirm="{{ __('Supprimer cette réponse ?') }}"
                                class="flex items-center gap-1 text-xs px-2 py-0.5 rounded-lg
                                    text-zinc-400 hover:bg-red-50 dark:hover:bg-red-900/20
                                    hover:text-red-400 transition-all"
                            >
                                <flux:icon name="trash" class="h-3 w-3" />
                            </button>
                        @endif
                    </div>

                    {{-- Droite : Like --}}
                    <div>
                        @auth
                            <button
                                wire:click="toggleLike({{ $reply->id }})"
                                @class([
                                    'flex items-center gap-1 text-xs px-2 py-0.5 rounded-lg transition-all font-medium',
                                    'bg-rose-50 dark:bg-rose-900/20 text-rose-500 ring-1 ring-rose-200 dark:ring-rose-500/30'
                                        => $reply->isLikedBy(auth()->user()),
                                    'text-zinc-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-400'
                                        => !$reply->isLikedBy(auth()->user()),
                                ])
                            >
                                <flux:icon
                                    name="heart"
                                    variant="{{ $reply->isLikedBy(auth()->user()) ? 'solid' : 'outline' }}"
                                    class="h-3 w-3"
                                />
                                @if($reply->likes_count > 0)
                                    {{ $this->formatCount($reply->likes_count) }}
                                @endif
                            </button>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>