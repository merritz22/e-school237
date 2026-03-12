<?php

use Livewire\Component;
use App\Models\Article;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;

new class extends Component
{
    public $stats = [
        'total_articles' => 0,
        'total_subjects' => 0,
        'total_supports' => 0,
    ];

    public function mount()
    {
        // Calcul des stats depuis la base de données
        $this->stats['total_articles'] = Article::count();
        $this->stats['total_subjects'] = EvaluationSubject::count();
        $this->stats['total_supports'] = EducationalResource::count();
    }

    // public function render()
    // {
    //     return view('livewire.dashboard.stats');
    // }
};
?>

<div class="py-3">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

            <flux:card class="p-4">
                <flux:heading size="sm">Articles</flux:heading>
                <div class="flex items-center mt-2 space-x-2">
                    <flux:icon name="document-text" class="text-primary w-6 h-6"/>
                    <span class="text-xl font-bold">{{ number_format($stats['total_articles']) }}</span>
                </div>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    Articles disponibles sur la plateforme
                </flux:text>
            </flux:card>

            <flux:card class="p-4">
                <flux:heading size="sm">Sujets</flux:heading>
                <div class="flex items-center mt-2 space-x-2">
                    <flux:icon name="academic-cap" class="text-primary w-6 h-6"/>
                    <span class="text-xl font-bold">{{ number_format($stats['total_subjects']) }}</span>
                </div>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    Sujets éducatifs disponibles
                </flux:text>
            </flux:card>

            <flux:card class="p-4">
                <flux:heading size="sm">Supports</flux:heading>
                <div class="flex items-center mt-2 space-x-2">
                    <flux:icon name="book-open" class="text-primary w-6 h-6"/>
                    <span class="text-xl font-bold">{{ number_format($stats['total_supports']) }}</span>
                </div>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    Documents éducatifs disponibles
                </flux:text>
            </flux:card>

        </div>
    </div>
</div>