<?php

use Livewire\Component;
use App\Models\EducationalResource;

new class extends Component
{
    public $latest_supports = [];

    public function mount()
    {
        $this->latest_supports = EducationalResource::with('subject') // catégorie ou matière
            ->latest()
            ->take(8)
            ->get();
    }
};
?>

<div class="mb-12">
    <div class="flex justify-between items-center mb-8">
        <flux:heading size="2xl" class="font-bold text-gray-900">
            Derniers Suports pédagogiques
        </flux:heading>
        <flux:button href="{{ route('resources.index') }}" icon:trailing="arrow-right">
            Voir tous les supports
        </flux:button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($latest_supports as $subject)
            <flux:card >
                <div class="w-full h-32 flex items-center justify-center">
                    <flux:icon name="book-open" class="w-12 h-12"/>
                </div>
                <flux:heading size="md" class="font-semibold mb-2 line-clamp-2">
                    <a href="{{ route('resources.show', $subject->id) }}">
                        {{ $subject->title }}
                    </a>
                </flux:heading>

                <flux:badge icon="sparkles" variant="solid" color="{{ $subject->is_free ? 'emerald' : 'yellow' }}">
                    {{ $subject->is_free ? 'Gratuit' : 'Premium' }}
                </flux:badge>

                <div class="flex items-center justify-between mt-2">
                    <flux:badge>
                        {{ $subject->level->name }}
                    </flux:badge>
                    @if($subject->subject->name)
                        <flux:badge color="blue">
                            {{ $subject->subject->name }}
                        </flux:badge>
                    @endif
                </div>
                <div class="w-full">
                    <div class="flex items-center justify-between text-sm">
                        <span>{{ number_format($subject->file_size / 1024, 0) }} KB</span>
                        <span>{{ $subject->downloads_count }} téléchargements</span>
                    </div>
                </div>
            </flux:card>
        @empty
            <div class="col-span-4 text-center py-12">
                <flux:text class="text-gray-500">Aucun support disponible.</flux:text>
            </div>
        @endforelse
    </div>
</div>