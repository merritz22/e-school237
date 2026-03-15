<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Level;
use App\Models\Profession;

new class extends Component
{
    public User $user;
    public $professions = [];
    public $allLevels   = [];

    #[Validate('required|string|max:255', message: 'L\'établissement est obligatoire.')]
    public string $establishment = '';

    #[Validate('required|date|before:today', message: 'La date de naissance est obligatoire.')]
    public string $birth_date = '';

    #[Validate('required|in:male,female,other', message: 'Veuillez sélectionner votre sexe.')]
    public string $gender = '';

    #[Validate('required|exists:professions,id', message: 'Veuillez sélectionner votre profession.')]
    public $profession_id = null;

    #[Validate('required|exists:levels,id', message: 'Veuillez sélectionner votre classe.')]
    public $current_level_id = null;

    public bool $needs_special_support = false;

    public function mount(): void
    {
        $this->professions = Profession::all();
        $this->allLevels   = Level::orderBy('name')->get();

        $info = $this->user->information;
        if ($info) {
            $this->establishment         = $info->establishment ?? '';
            $this->birth_date            = $info->birth_date?->format('Y-m-d') ?? '';
            $this->gender                = $info->gender ?? '';
            $this->profession_id         = $info->profession_id;
            $this->current_level_id      = $info->current_level_id;
            $this->needs_special_support = $info->needs_special_support;
        }
    }

    public function updatedCurrentLevelId($value): void
    {
        // Notifie SubjectsSection que la classe a changé
        $this->dispatch('level-changed', levelId: $value);
    }

    #[On('save-profile')]
    public function save(): void
    {
        $this->validate();

        $isComplete = !empty($this->establishment)
            && !empty($this->birth_date)
            && !empty($this->gender)
            && !empty($this->profession_id)
            && !empty($this->current_level_id);

        $this->user->information()->updateOrCreate(
            ['user_id' => $this->user->id],
            [
                'establishment'         => $this->establishment,
                'birth_date'            => $this->birth_date,
                'gender'                => $this->gender,
                'profession_id'         => $this->profession_id,
                'current_level_id'      => $this->current_level_id,
                'needs_special_support' => $this->needs_special_support,
                'is_complete'           => $isComplete,
            ]
        );

        $this->dispatch('profile-saved', isComplete: $isComplete);
    }
};
?>

<div>
    <flux:heading size="sm" class="mb-4 flex items-center gap-2">
        <flux:icon name="identification" class="w-4 h-4" />
        {{ __('app.profile.sections.additional') }}
    </flux:heading>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Établissement --}}
        <div class="relative">
            @if(empty($establishment))
                <span class="absolute -top-1 -right-1 z-10 w-3 h-3 rounded-full
                    bg-red-500 animate-pulse"></span>
            @endif
            <flux:input wire:model="establishment"
                        label="{{ __('app.profile.fields.establishment') }} *"
                        placeholder="Ex: Lycée de Yaoundé"
                        icon="building-library" />
            <flux:error name="establishment" />
        </div>

        {{-- Date de naissance --}}
        <div class="relative">
            @if(empty($birth_date))
                <span class="absolute -top-1 -right-1 z-10 w-3 h-3 rounded-full
                    bg-red-500 animate-pulse"></span>
            @endif
            <flux:input wire:model="birth_date"
                        label="{{ __('app.profile.fields.birth_date') }} *"
                        type="date" />
            <flux:error name="birth_date" />
        </div>

        {{-- Sexe --}}
        <div class="relative">
            @if(empty($gender))
                <span class="absolute -top-1 -right-1 z-10 w-3 h-3 rounded-full
                    bg-red-500 animate-pulse"></span>
            @endif
            <flux:select wire:model="gender"
                         label="{{ __('app.profile.fields.gender') }} *">
                <option value="">{{ __('app.profile.fields.gender_placeholder') }}</option>
                <option value="male">{{ __('app.profile.gender.male') }}</option>
                <option value="female">{{ __('app.profile.gender.female') }}</option>
                <option value="other">{{ __('app.profile.gender.other') }}</option>
            </flux:select>
            <flux:error name="gender" />
        </div>

        {{-- Profession --}}
        <div class="relative">
            @if(empty($profession_id))
                <span class="absolute -top-1 -right-1 z-10 w-3 h-3 rounded-full
                    bg-red-500 animate-pulse"></span>
            @endif
            <flux:select wire:model="profession_id"
                         label="{{ __('app.profile.fields.profession') }} *">
                <option value="">{{ __('app.profile.fields.profession_placeholder') }}</option>
                @foreach($professions as $profession)
                    <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                @endforeach
            </flux:select>
            <flux:error name="profession_id" />
        </div>

        {{-- Classe --}}
        <div class="relative">
            @if(empty($current_level_id))
                <span class="absolute -top-1 -right-1 z-10 w-3 h-3 rounded-full
                    bg-red-500 animate-pulse"></span>
            @endif
            <flux:select wire:model.live="current_level_id"
                         label="{{ __('app.profile.fields.current_level') }} *">
                <option value="">{{ __('app.profile.fields.level_placeholder') }}</option>
                @foreach($allLevels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            </flux:select>
            <flux:error name="current_level_id" />
        </div>

    </div>

    {{-- Suivi particulier --}}
    <div class="mt-4">
        <flux:checkbox wire:model="needs_special_support"
                       label="{{ __('app.profile.fields.special_support') }}" />
    </div>
</div>