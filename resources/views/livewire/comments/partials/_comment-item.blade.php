<div
    wire:key="comment-{{ $comment->id }}"
    class="group"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
>
    <div class="flex gap-3 py-3">

        {{-- Avatar + fil vertical --}}
        <div class="flex flex-col items-center gap-1 shrink-0">
            @if($comment->user->avatar_url ?? false)
                <img src="{{ asset('storage/' . $comment->user->avatar_url) }}"
                     class="w-9 h-9 rounded-full object-cover ring-2 ring-zinc-100 dark:ring-zinc-800" />
            @else
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-primary/60
                            flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
            @endif
            @if($comment->replies->isNotEmpty())
                <div class="w-px flex-1 bg-zinc-100 dark:bg-zinc-800 min-h-4"></div>
            @endif
        </div>

        <div class="flex-1 min-w-0 space-y-1.5">

            <div
                @class([
                    'rounded-2xl rounded-tl-sm shadow-sm border transition-colors duration-200',
                    'border-amber-300 dark:border-amber-500/60 bg-amber-50/50 dark:bg-amber-900/10 ring-1 ring-amber-200 dark:ring-amber-500/30'
                        => $this->isMentionedWithoutReaction($comment),
                    'bg-white dark:bg-zinc-900 border-zinc-100 dark:border-zinc-800 hover:border-zinc-200 dark:hover:border-zinc-700'
                        => !$this->isMentionedWithoutReaction($comment),
                ])
            >
                {{-- Corps principal --}}
                <div class="px-4 pt-3 pb-2">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-semibold text-sm text-zinc-800 dark:text-zinc-100">
                                {{ $comment->user->name }}
                            </span>
                            @if($comment->is_pinned)
                                <span class="inline-flex items-center gap-1 text-xs bg-amber-50 dark:bg-amber-900/20
                                            text-amber-600 dark:text-amber-400 px-2 py-0.5 rounded-full font-medium">
                                    <flux:icon name="bookmark" variant="solid" class="h-3 w-3" />
                                    {{ __('Épinglé') }}
                                </span>
                            @endif
                            @if($comment->user_id === auth()->id())
                                <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">
                                    {{ __('Vous') }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-zinc-400 shrink-0 tabular-nums">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Badge mention --}}
                    @if($this->isMentionedWithoutReaction($comment))
                        <div class="flex items-center gap-1 mb-2 text-xs text-amber-600 dark:text-amber-400 font-medium animate-pulse">
                            <flux:icon name="at-symbol" class="h-3.5 w-3.5" />
                            {{ __('Vous avez été mentionné · Réagissez pour masquer') }}
                        </div>
                    @endif

                    {{-- Texte --}}
                    @if($comment->is_deleted)
                        @if($comment->deleted_display === 'strikethrough')
                            <p class="text-sm text-zinc-400 line-through leading-relaxed">
                                {!! $this->renderMentions($comment->body) !!}
                            </p>
                        @else
                            <div x-data="{ revealed: false }" class="relative cursor-pointer" @click="revealed = !revealed">
                                <p :class="revealed ? '' : 'blur-sm select-none'"
                                class="text-sm text-zinc-400 leading-relaxed transition-all duration-300">
                                    {!! $this->renderMentions($comment->body) !!}
                                </p>
                                <span x-show="!revealed"
                                    class="absolute inset-0 flex items-center justify-center gap-1 text-xs text-zinc-400 font-medium">
                                    <flux:icon name="eye" class="h-3.5 w-3.5" />
                                    {{ __('Cliquer pour révéler') }}
                                </span>
                            </div>
                        @endif
                        <p class="text-xs text-zinc-400 mt-1.5 italic flex items-center gap-1">
                            <flux:icon name="trash" class="h-3 w-3" />
                            {{ __('Commentaire supprimé') }}
                        </p>

                    @elseif($editingId === $comment->id)
                        <textarea
                            x-model="$wire.editBody"
                            wire:loading.attr="disabled"
                            wire:target="saveEdit"
                            rows="3"
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700
                                rounded-xl px-3 py-2 text-sm resize-none focus:outline-none
                                focus:ring-2 focus:ring-primary/30 mt-1 disabled:opacity-50"
                        ></textarea>
                        <div class="flex gap-2 mt-2">
                            <flux:button wire:click="saveEdit" wire:loading.attr="disabled"
                                wire:target="saveEdit" variant="primary" size="sm" class="rounded-xl" icon="check">
                                <span wire:loading.remove wire:target="saveEdit">{{ __('Sauvegarder') }}</span>
                                <span wire:loading wire:target="saveEdit">{{ __('Sauvegarde...') }}</span>
                            </flux:button>
                            <flux:button wire:click="cancelEdit" size="sm" class="rounded-xl" icon="x-mark">
                                {{ __('Annuler') }}
                            </flux:button>
                        </div>

                    @else
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                            {!! $this->renderMentions($comment->body) !!}
                        </p>
                    @endif
                </div>

                {{-- Séparateur + Footer actions --}}
                @if(!$comment->is_deleted && $editingId !== $comment->id)
                    <div class="border-t border-zinc-100 dark:border-zinc-800 px-3 py-1.5 flex items-center justify-between">

                        {{-- Gauche : Supprimer --}}
                        <div class="flex items-center">
                            @if(auth()->id() === $comment->user_id || auth()->user()?->role === 'admin')
                                <button
                                    wire:click="deleteComment({{ $comment->id }})"
                                    wire:confirm="{{ __('Supprimer ce commentaire ?') }}"
                                    class="flex items-center gap-1 text-xs px-2 py-1 rounded-lg
                                        text-zinc-400 hover:bg-red-50 dark:hover:bg-red-900/20
                                        hover:text-red-400 transition-all duration-150"
                                >
                                    <flux:icon name="trash" class="h-3.5 w-3.5" />
                                </button>
                            @endif
                        </div>

                        {{-- Droite : Like + Répondre + Éditer --}}
                        <div class="flex items-center gap-1">
                            @auth
                                {{-- Like --}}
                                <button
                                    wire:click="toggleLike({{ $comment->id }})"
                                    @class([
                                        'flex items-center gap-1 text-xs px-2 py-1 rounded-lg transition-all duration-150 font-medium',
                                        'bg-rose-50 dark:bg-rose-900/20 text-rose-500 ring-1 ring-rose-200 dark:ring-rose-500/30'
                                            => $comment->isLikedBy(auth()->user()),
                                        'text-zinc-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-400'
                                            => !$comment->isLikedBy(auth()->user()),
                                    ])
                                >
                                    <flux:icon
                                        name="heart"
                                        variant="{{ $comment->isLikedBy(auth()->user()) ? 'solid' : 'outline' }}"
                                        class="h-3.5 w-3.5"
                                    />
                                    @if($comment->likes_count > 0)
                                        <span>{{ $this->formatCount($comment->likes_count) }}</span>
                                    @endif
                                </button>

                                {{-- Répondre --}}
                                <button
                                    wire:click="$set('replyingTo', {{ $replyingTo === $comment->id ? 'null' : $comment->id }})"
                                    @class([
                                        'flex items-center gap-1 text-xs px-2 py-1 rounded-lg transition-all duration-150 font-medium',
                                        'bg-sky-50 dark:bg-sky-900/20 text-sky-500 ring-1 ring-sky-200 dark:ring-sky-500/30'
                                            => $replyingTo === $comment->id,
                                        'text-zinc-400 hover:bg-sky-50 dark:hover:bg-sky-900/20 hover:text-sky-400'
                                            => $replyingTo !== $comment->id,
                                    ])
                                >
                                    <flux:icon name="arrow-uturn-left" class="h-3.5 w-3.5" />
                                    @if($comment->replies->count() > 0)
                                        <span>{{ $this->formatCount($comment->replies->count()) }}</span>
                                    @endif
                                </button>
                            @endauth

                            {{-- Éditer --}}
                            @if(auth()->id() === $comment->user_id)
                                <button
                                    wire:click="startEdit({{ $comment->id }})"
                                    class="flex items-center gap-1 text-xs px-2 py-1 rounded-lg
                                        text-zinc-400 hover:bg-violet-50 dark:hover:bg-violet-900/20
                                        hover:text-violet-400 transition-all duration-150"
                                >
                                    <flux:icon name="pencil-square" class="h-3.5 w-3.5" />
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Formulaire de réponse --}}
            @if($replyingTo === $comment->id)
                <div
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex gap-2.5 mt-1"
                >
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary to-primary/60
                                flex items-center justify-center text-white text-xs font-bold shrink-0 mt-1">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 bg-zinc-50 dark:bg-zinc-800 rounded-2xl rounded-tl-sm
                                border border-zinc-200 dark:border-zinc-700 px-3 py-2.5
                                focus-within:ring-2 focus-within:ring-primary/30 transition-all">
                        <textarea
                            x-model="$wire.replyBody"
                            wire:loading.attr="disabled"
                            wire:target="postReply"
                            rows="2"
                            placeholder="{{ __('Répondre à') }} {{ $comment->user->name }}..."
                            class="w-full bg-transparent resize-none text-sm focus:outline-none
                                   text-zinc-700 dark:text-zinc-200 placeholder-zinc-400 disabled:opacity-50"
                        ></textarea>
                        <div class="flex justify-end gap-2 mt-2 pt-2 border-t border-zinc-200 dark:border-zinc-700">
                            <flux:button wire:click="$set('replyingTo', null)" size="sm" class="rounded-xl" icon="x-mark">
                                {{ __('Annuler') }}
                            </flux:button>
                            <flux:button wire:click="postReply" wire:loading.attr="disabled"
                                wire:target="postReply" variant="primary" size="sm" class="rounded-xl" icon="paper-airplane">
                                <span wire:loading.remove wire:target="postReply">{{ __('Répondre') }}</span>
                                <span wire:loading wire:target="postReply">{{ __('Envoi...') }}</span>
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Réponses --}}
            @if($comment->replies->isNotEmpty())
                <div class="space-y-3 mt-2">
                    @foreach($comment->replies as $reply)
                        @include('livewire.comments.partials._reply-item', ['reply' => $reply])
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>