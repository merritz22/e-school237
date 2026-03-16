<x-layouts.app>

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

        @media (max-width: 640px) {
            .chat-panel {
                position: fixed !important;
                right: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                top: auto !important;
                transform: none !important;
                width: 100% !important;
                max-width: 100% !important;
                max-height: 70vh !important;
                border-radius: 1.25rem 1.25rem 0 0 !important;
            }
        }
    </style>

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
        class="fixed right-4 sm:right-6 bottom-6 sm:bottom-auto sm:top-1/2 sm:-translate-y-1/2 z-50 flex flex-col items-end gap-3"
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
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4 scale-95"
            x-cloak
            class="chat-panel
                sm:absolute sm:right-14 sm:top-1/2 sm:-translate-y-1/2
                w-[380px] max-w-[92vw] sm:max-w-[380px]
                max-h-[70vh] sm:max-h-[75vh]
                flex flex-col
                bg-white dark:bg-zinc-900
                border border-zinc-200 dark:border-zinc-700
                rounded-2xl shadow-2xl shadow-black/20 dark:shadow-black/50
                overflow-hidden"
        >
            {{-- Handle mobile --}}
            <div class="sm:hidden flex justify-center pt-2.5 pb-1 shrink-0">
                <div class="w-10 h-1 rounded-full bg-zinc-300 dark:bg-zinc-600"></div>
            </div>

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