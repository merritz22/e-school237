<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Level;

new class extends Component
{
    public User $user;
    public $allSubjects = [];

    #[Validate('nullable|array')]
    public array $best_subject_ids = [];

    #[Validate('nullable|array')]
    public array $worst_subject_ids = [];

    public function mount(): void
    {
        $info = $this->user->information;

        $this->allSubjects = $info?->current_level_id
            ? Level::find($info->current_level_id)?->subjects()->orderBy('name')->get() ?? collect()
            : collect();

        $this->best_subject_ids  = $this->user->bestSubjects->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->worst_subject_ids = $this->user->worstSubjects->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    // Recharge les matières quand AdditionalSection change la classe
    #[On('level-changed')]
    public function onLevelChanged(int $levelId): void
    {
        $this->allSubjects       = $levelId
            ? Level::find($levelId)?->subjects()->orderBy('name')->get() ?? collect()
            : collect();
        $this->best_subject_ids  = [];
        $this->worst_subject_ids = [];
    }

    #[On('save-profile')]
    public function save(): void
    {
        $this->validate();
        $this->user->bestSubjects()->sync($this->best_subject_ids);
        $this->user->worstSubjects()->sync($this->worst_subject_ids);
    }
};
?>

<div>
    @if(count($allSubjects) > 0)
    <div>
        <flux:heading size="sm" class="mb-4 flex items-center gap-2">
            <flux:icon name="academic-cap" class="w-4 h-4" />
            {{ __('app.profile.sections.subjects') }}
        </flux:heading>
    
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
            {{-- Matières fortes --}}
            <div>
                <flux:label class="mb-2 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    {{ __('app.profile.fields.best_subjects') }}
                </flux:label>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    @foreach($allSubjects as $subject)
                        <flux:checkbox
                            wire:model="best_subject_ids"
                            value="{{ $subject->id }}"
                            label="{{ $subject->name }}"
                        />
                    @endforeach
                </div>
            </div>
    
            {{-- Matières faibles --}}
            <div>
                <flux:label class="mb-2 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    {{ __('app.profile.fields.worst_subjects') }}
                </flux:label>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    @foreach($allSubjects as $subject)
                        <flux:checkbox
                            wire:model="worst_subject_ids"
                            value="{{ $subject->id }}"
                            label="{{ $subject->name }}"
                        />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>