{{-- livewire/articles/show.blade.php --}}
<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">

    {{-- ═══════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════ --}}
    <div class="relative">
        @if($article->featured_image)
            <div class="h-72 md:h-96 w-full overflow-hidden">
                <img src="{{ Storage::url($article->featured_image) }}"
                     alt="{{ $article->title }}"
                     class="w-full h-full object-cover"/>
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-900/60 to-transparent"></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 px-4 pb-8 md:px-10">
                <div class="max-w-5xl mx-auto">
                    <flux:badge color="{{ config('theme.primary') }}" class="mb-3">
                        {{ $article->subject?->name }}
                    </flux:badge>
                    <h1 class="text-2xl md:text-4xl font-bold text-white leading-tight">
                        {{ $article->title }}
                    </h1>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 px-4 py-10 md:px-10">
                <div class="max-w-5xl mx-auto">
                    <flux:badge color="{{ config('theme.primary') }}" class="mb-3">
                        {{ $article->subject?->name }}
                    </flux:badge>
                    <h1 class="text-2xl md:text-4xl font-bold leading-tight text-zinc-900 dark:text-white">
                        {{ $article->title }}
                    </h1>
                </div>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════
         CONTENU PRINCIPAL
    ═══════════════════════════════════════════ --}}
    <div class="mx-auto py-8 space-y-8">

        {{-- ─── Barre méta ─────────────────────────── --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 px-5 py-4
                    flex flex-wrap items-center justify-between gap-4">

            {{-- Auteur --}}
            <div class="flex items-center gap-3">
                <img src="{{ $article->author->avatar_url
                        ? Storage::url($article->author->avatar_url)
                        : 'https://ui-avatars.com/api/?name='.urlencode($article->author->name).'&background=3b82f6&color=fff' }}"
                     alt="{{ $article->author->name }}"
                     class="w-10 h-10 rounded-full object-cover ring-2 ring-{{ config('theme.primary') }}-200"/>
                <div>
                    <div class="font-semibold text-sm text-zinc-800 dark:text-zinc-200">
                        {{ $article->author->name }}
                    </div>
                    <div class="text-xs text-zinc-400">
                        {{ $article->published_at?->format('d/m/Y à H:i') ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Stats + actions --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-zinc-500">
                <span class="flex items-center gap-1.5">
                    <flux:icon name="clock" class="w-4 h-4"/>
                    {{ $article->reading_time }} min
                </span>
                <span class="flex items-center gap-1.5">
                    <flux:icon name="eye" class="w-4 h-4"/>
                    {{ number_format($article->views_count) }} vues
                </span>

                <flux:button icon="hand-thumb-up"
                             wire:click="toggleLike"
                             variant="{{ $liked ? 'filled' : 'ghost' }}"
                             size="sm">
                    {{ $this->likesFormatted }}
                </flux:button>

                {{-- WhatsApp --}}
                <a href="https://wa.me/?text={{ urlencode($article->title.' - '.route('articles.show', $article->slug)) }}"
                   target="_blank" title="WhatsApp" class="hover:opacity-75 transition-opacity">
                    <svg class="w-6 h-6 rounded-md" fill="currentColor" viewBox="0 0 512 512">
                        <rect width="512" height="512" rx="15%" fill="#25d366"/>
                        <path fill="#25d366" stroke="#fff" stroke-width="26" d="M123 393l14-65a138 138 0 1150 47z"/>
                        <path fill="#fff" d="M308 273c-3-2-6-3-9 1l-12 16c-3 2-5 3-9 1-15-8-36-17-54-47-1-4 1-6 3-8l9-14c2-2 1-4 0-6l-12-29c-3-8-6-7-9-7h-8c-2 0-6 1-10 5-22 22-13 53 3 73 3 4 23 40 66 59 32 14 39 12 48 10 11-1 22-10 27-19 1-3 6-16 2-18"/>
                    </svg>
                </a>

                {{-- LinkedIn --}}
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('articles.show', $article->slug)) }}"
                   target="_blank" title="LinkedIn" class="hover:opacity-75 transition-opacity text-blue-600">
                    <svg class="w-6 h-6 rounded-md" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z" clip-rule="evenodd"/>
                    </svg>
                </a>

                {{-- Copier lien --}}
                <flux:button icon="link" variant="ghost" size="sm"
                             onclick="navigator.clipboard.writeText('{{ route('articles.show', $article->slug) }}').then(()=>alert('Lien copié !'))">
                    Copier
                </flux:button>
            </div>
        </div>

        {{-- ─── Iframe article ─────────────────────── --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">

            {{-- Barre décorative style navigateur --}}
            <div class="flex items-center gap-2 px-4 py-2.5
                        border-b border-zinc-100 dark:border-zinc-800
                        bg-zinc-50 dark:bg-zinc-900/50">
                <span class="w-3 h-3 rounded-full bg-red-400"></span>
                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                <span class="w-3 h-3 rounded-full bg-green-400"></span>
                <span class="text-xs text-zinc-400 ml-2 font-mono">article</span>
            </div>

            <iframe id="article-iframe"
                    style="width:100%; border:none; height:80vh; display:block;"
                    srcdoc="{{ $article->content }}"
                    title="{{ $article->title }}">
            </iframe>
        </div>

        {{-- ─── Articles similaires ────────────────── --}}
        @if($related_articles->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-zinc-800 dark:text-white mb-5">
                    Articles similaires
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($related_articles as $related)
                        <a wire:navigate href="{{ route('articles.show', $related->slug) }}"
                           class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200
                                  dark:border-zinc-800 overflow-hidden hover:shadow-md
                                  transition-all duration-200 flex flex-col">

                            @if($related->featured_image)
                                <img src="{{ Storage::url($related->featured_image) }}"
                                     alt="{{ $related->title }}"
                                     class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300"/>
                            @else
                                <div class="w-full h-32 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                    <flux:icon name="document-text" class="w-8 h-8 text-zinc-300"/>
                                </div>
                            @endif

                            <div class="p-4 flex flex-col gap-2 flex-1">
                                <p class="font-semibold text-sm text-zinc-800 dark:text-zinc-200
                                          line-clamp-2 leading-snug">
                                    {{ $related->title }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-zinc-400 mt-auto">
                                    <span>{{ $related->published_at?->format('d/m/Y') ?? '-' }}</span>
                                    <span class="flex items-center gap-1">
                                        <flux:icon name="eye" class="w-3 h-3"/>
                                        {{ number_format($related->views_count) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ─── Commentaires ───────────────────────── --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200
                    dark:border-zinc-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 flex items-center gap-2">
                <flux:icon name="chat-bubble-left-right"
                           class="w-5 h-5 text-{{ config('theme.primary') }}-500"/>
                <h2 class="text-lg font-bold text-zinc-800 dark:text-white">Commentaires</h2>
            </div>
            <div class="p-1">
                <livewire:comments.comment :model="$article" />
            </div>
        </div>

    </div>
</div>

@script
<script>
    function resizeIframe() {
        const iframe = document.getElementById('article-iframe');
        if (!iframe) return;
        try {
            const doc = iframe.contentDocument || iframe.contentWindow?.document;
            if (!doc?.body) return;
            const h = Math.max(doc.body.scrollHeight, doc.documentElement.scrollHeight);
            if (h > 100) iframe.style.height = (h + 40) + 'px';
        } catch (e) {}
    }

    setTimeout(resizeIframe, 500);
    setTimeout(resizeIframe, 1500);
    setTimeout(resizeIframe, 3000);
</script>
@endscript