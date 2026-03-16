<x-layouts.app>

    {{-- Custom scrollbar CSS --}}
    <style>
        .comments-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #a855f7 transparent;
        }
        .comments-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .comments-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .comments-scrollbar::-webkit-scrollbar-thumb {
            background: #a855f7;
            border-radius: 999px;
        }
        .comments-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9333ea;
        }
    </style>

    {{-- Définir chatPanel AVANT le x-data --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatPanel', () => ({
                open: false,
                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },
                scrollToBottom() {
                    const container = this.$el.querySelector('.comments-scrollbar');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            }));
        });
    </script>

    {{-- Vue principale --}}
    <livewire:subjects.show :subject="$subject" wire:lazy />

    {{-- Bouton flottant chat --}}
    <div
        x-data="chatPanel()"
        class="fixed right-6 top-1/2 -translate-y-1/2 z-50 flex flex-col items-end gap-3"
    >
        {{-- Bouton toggle --}}
        <button
            x-on:click="toggle()"
            x-bind:class="open ? 'bg-purple-600 shadow-purple-500/40' : 'bg-white dark:bg-zinc-800 shadow-zinc-300/50 dark:shadow-black/40'"
            class="group relative flex items-center justify-center w-12 h-12 rounded-full shadow-xl
                border border-zinc-200 dark:border-zinc-700
                transition-all duration-300 hover:scale-110 active:scale-95"
            title="Commentaires"
        >
            <span x-show="!open" class="transition-opacity duration-200">
                <flux:icon name="chat-bubble-left-right"
                    class="w-5 h-5 text-zinc-600 dark:text-zinc-300" />
            </span>
            <span x-show="open" class="transition-opacity duration-200">
                <flux:icon name="x-mark" class="w-5 h-5 text-white" />
            </span>
        </button>

        {{-- Panneau commentaires --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-4 scale-95"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-4 scale-95"
            x-cloak
            class="absolute right-14 top-1/2 -translate-y-1/2
                w-[380px] max-w-[90vw]
                max-h-[75vh]
                flex flex-col
                bg-white dark:bg-zinc-900
                border border-zinc-200 dark:border-zinc-700
                rounded-2xl shadow-2xl shadow-black/20 dark:shadow-black/50
                overflow-hidden"
        >
            {{-- Header panneau --}}
            <div class="flex items-center justify-between px-4 py-3 shrink-0
                border-b border-zinc-100 dark:border-zinc-800
                bg-zinc-50 dark:bg-zinc-800/60">
                <div class="flex items-center gap-2">
                    <flux:icon name="chat-bubble-left-right" class="w-4 h-4 text-purple-500" />
                    <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">
                        Commentaires
                    </span>
                </div>
                <button
                    x-on:click="toggle()"
                    class="p-1 rounded-lg hover:bg-zinc-200 dark:hover:bg-zinc-700
                        text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300
                        transition-colors"
                >
                    <flux:icon name="x-mark" class="w-4 h-4" />
                </button>
            </div>

            {{-- Contenu scrollable --}}
            <div class="flex-1 overflow-y-auto comments-scrollbar">
                <livewire:comments.comment
                    :model="$subject"
                    deleted-display="strikethrough"
                    wire:lazy
                />
            </div>
        </div>
    </div>

</x-layouts.app>