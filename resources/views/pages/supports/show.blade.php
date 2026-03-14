<x-layouts.app>
    <livewire:supports.show :resource="$resource" wire:lazy />
    
    <livewire:comments.comment
        :model="$resource"
        deleted-display="strikethrough"
        wire:lazy
    />
</x-layouts.app>