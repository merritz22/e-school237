<x-layouts.app>
    <livewire:subjects.show :subject="$subject" wire:lazy />

    <livewire:comments.comment
        :model="$subject"
        deleted-display="strikethrough"
        wire:lazy
    />
</x-layouts.app>