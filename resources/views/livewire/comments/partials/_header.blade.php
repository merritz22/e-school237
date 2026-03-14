<div class="flex items-center gap-3">
    <div class="flex items-center justify-center w-10 h-10 rounded-2xl bg-primary/10">
        <flux:icon name="chat-bubble-left-right" class="w-5 h-5 text-primary" />
    </div>
    <div>
        <flux:heading size="lg" class="font-bold leading-none">
            {{ __('Commentaires') }}
        </flux:heading>
        <p class="text-xs text-zinc-400 mt-0.5">
            {{ $total > 0
                ? $total . ' ' . __('contribution(s)')
                : __('Aucun commentaire pour l\'instant') }}
        </p>
    </div>
</div>