<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EducationalResource;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithPagination;

    public $subject_id = '';
    public $level_id   = '';
    public $search     = '';

    // Filtre classe
    public bool $classFilterEnabled    = false;
    public array $subscribedLevelIds   = [];
    public array $subscribedSubjectIds = [];

    protected $queryString = [
        'subject_id' => ['except' => ''],
        'level_id'   => ['except' => ''],
        'search'     => ['except' => ''],
    ];

    public function mount(): void
    {
        if (Auth::check() && Auth::user()->role != 'admin') {
            $user = Auth::user();
            $info = $user->information;

            $hasSubscription = $user->subscriptions()
                ->where('status', 'active')
                ->exists();

            $this->classFilterEnabled = $hasSubscription
                && ($info?->class_filter_enabled ?? false);

            if ($this->classFilterEnabled) {
                $this->subscribedLevelIds = $user->subscriptions()
                    ->where('status', 'active')
                    ->pluck('level_id')
                    ->filter()
                    ->toArray();

                $this->subscribedSubjectIds = Level::whereIn('id', $this->subscribedLevelIds)
                    ->with('subjects')
                    ->get()
                    ->flatMap(fn($level) => $level->subjects->pluck('id'))
                    ->unique()
                    ->toArray();
            }
        }
    }

    public function updatingSubjectId(): void { $this->resetPage(); }
    public function updatingLevelId(): void   { $this->resetPage(); }
    public function updatingSearch(): void    { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['subject_id', 'level_id', 'search']);
        $this->resetPage();
    }

    public function render()
    {
        $query = EducationalResource::with('subject', 'level')
            ->where('is_approved', 1);

        $user = null;
        $info = null; // Déclaré ici pour être accessible dans tout le scope de la méthode

        // Récupération de l'utilisateur connecté et de ses informations
        if (Auth::check() && Auth::user()->role != 'admin') {
            $user = Auth::user();
            $info = $user->information;
        }

        

        $this->show_premium_preview = $user->subscriptions()
            ->where('status', 'active')
            ->exists();

        // ===== FILTRE PAR CLASSE (abonnements) =====
        // Priorité 1 : filtre par abonnement activé manuellement
        if ($this->classFilterEnabled) {
            if (!empty($this->subscribedLevelIds)) {
                $query->whereHas('level', fn($q) =>
                    $q->whereIn('id', $this->subscribedLevelIds)
                );
            }
            if (!empty($this->subscribedSubjectIds)) {
                $query->whereHas('subject', fn($q) =>
                    $q->whereIn('id', $this->subscribedSubjectIds)
                );
            }
        }
        // Priorité 2 : aucun filtre manuel de niveau → on applique le niveau courant de l'utilisateur
        elseif ($info && empty($this->level_id)) {
            $query->where('level_id', $info?->current_level_id);
        }

        // ===== FILTRES MANUELS =====
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->subject_id) {
            $query->whereHas('subject', fn($q) =>
                $q->where('slug', $this->subject_id)
            );
        }

        if ($this->level_id) {
            $query->whereHas('level', fn($q) =>
                $q->where('slug', $this->level_id)
            );
        }

        // ===== OPTIONS DES DROPDOWNS =====

        // Subjects : filtrés selon le contexte (abonnement > niveau courant > tous)
        $subjects = match(true) {
            // Filtre abonnement actif avec des matières définies
            $this->classFilterEnabled && !empty($this->subscribedSubjectIds)
                => Subject::where('is_active', 1)
                    ->whereIn('id', $this->subscribedSubjectIds)
                    ->get(),

            // Utilisateur connecté sans filtre abonnement → matières liées à son niveau courant
            $info !== null
                => Subject::where('is_active', 1)
                    ->whereIn('id',
                        Level::where('id', $info->current_level_id)
                            ->with('subjects')
                            ->get()
                            ->flatMap(fn($level) => $level->subjects->pluck('id'))
                            ->unique()
                            ->toArray()
                    )
                    ->get(),

            // Visiteur non connecté → toutes les matières actives
            default => Subject::where('is_active', 1)->get(),
        };

        // Levels : filtrés selon le contexte (abonnement > niveau courant > tous)
        $levels = match(true) {
            // Filtre abonnement actif avec des niveaux définis
            $this->classFilterEnabled && !empty($this->subscribedLevelIds)
                => Level::where('is_active', 1)
                    ->whereIn('id', $this->subscribedLevelIds)
                    ->get(),

            // Utilisateur connecté sans filtre abonnement → uniquement son niveau courant
            $info !== null
                => Level::where('is_active', 1)
                    ->where('id', $info->current_level_id)
                    ->get(),

            // Visiteur non connecté → tous les niveaux actifs
            default => Level::where('is_active', 1)->get(),
        };

        return view('livewire.supports.index', [
            'resources' => $query->latest()->paginate(15),
            'subjects'  => $subjects,
            'levels'    => $levels,
            'show_premium_preview' => $show_premium_preview
        ]);
    }
};
?>