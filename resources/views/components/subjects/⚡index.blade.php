<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\EvaluationSubject;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithPagination;

    public $subject_id = '';
    public $level_id   = '';
    public $type       = '';
    public $year       = '';
    public $sort       = 'latest';
    public $search     = '';

    // Données pour les filtres
    public $filter_subjects = [];
    public $levels          = [];
    public $types           = [];
    public $years           = [];

    // Filtre classe actif
    public bool $classFilterEnabled  = false;
    public array $subscribedLevelIds = [];
    public array $subscribedSubjectIds = [];

    protected $queryString = [
        'subject_id' => ['except' => ''],
        'level_id'   => ['except' => ''],
        'type'       => ['except' => ''],
        'year'       => ['except' => ''],
        'sort'       => ['except' => 'latest'],
        'search'     => ['except' => ''],
    ];

    public function mount(): void
    {
        // Charger les filtres
        $this->filter_subjects = Subject::where('is_active', 1)->get();
        $this->levels          = Level::where('is_active', 1)->get();
        $this->types           = EvaluationSubject::distinct()->pluck('type')->filter()->sort()->values();
        $this->years           = EvaluationSubject::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Charger les infos de filtre classe si connecté
        if (Auth::check()) {
            $user = Auth::user();
            $info = $user->information;

            $this->filter_subjects = Subject::where('is_active', 1)->whereIn('id',
                \App\Models\Level::where('id', $info->current_level_id)
                    ->with('subjects')
                    ->get()
                    ->flatMap(fn($level) => $level->subjects->pluck('id'))
                    ->unique()
                    ->toArray())
                ->get();
            $this->levels          = Level::where('is_active', 1)->where('id',$info->current_level_id)->get();

            // Filtre activé uniquement si abonné ET option cochée
            $hasSubscription = $user->subscriptions()
                ->where('status', 'active')
                ->exists();

            $this->classFilterEnabled = $hasSubscription
                && ($info?->class_filter_enabled ?? false);

            if ($this->classFilterEnabled) {
                // Récupérer les levels auxquels l'user est abonné
                $this->subscribedLevelIds = $user->subscriptions()
                    ->where('status', 'active')
                    ->pluck('level_id')
                    ->filter()
                    ->toArray();

                // Récupérer les matières de ces levels
                $this->subscribedSubjectIds = \App\Models\Level::whereIn('id', $this->subscribedLevelIds)
                    ->with('subjects')
                    ->get()
                    ->flatMap(fn($level) => $level->subjects->pluck('id'))
                    ->unique()
                    ->toArray();

                // Restreindre les filtres disponibles aux classes/matières abonnées
                $this->filter_subjects = Subject::where('is_active', 1)
                    ->whereIn('id', $this->subscribedSubjectIds)
                    ->get();

                $this->levels = Level::where('is_active', 1)
                    ->whereIn('id', $this->subscribedLevelIds)
                    ->get();
            }
        }
    }

    public function updating($property): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->subject_id = '';
        $this->level_id   = '';
        $this->type       = '';
        $this->year       = '';
        $this->sort       = 'latest';
        $this->search     = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = EvaluationSubject::with('subject', 'level');

        // Par défaut, on affiche les produits liés à la classe de l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            $info = $user->information;
            $query->where('level_id',$info->current_level_id);
        }

        // ===== FILTRE PAR CLASSE (si activé) =====
        if ($this->classFilterEnabled) {
            if (!empty($this->subscribedLevelIds)) {
                $query->whereIn('level_id', $this->subscribedLevelIds);
            }
            if (!empty($this->subscribedSubjectIds)) {
                $query->whereIn('subject_id', $this->subscribedSubjectIds);
            }
        }

        // ===== FILTRES MANUELS =====
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }
        if ($this->subject_id) {
            $query->where('subject_id', $this->subject_id);
        }
        if ($this->level_id) {
            $query->where('level_id', $this->level_id);
        }
        if ($this->type) {
            $query->where('type', $this->type);
        }
        if ($this->year) {
            $query->whereYear('created_at', $this->year);
        }

        // ===== TRI =====
        match ($this->sort) {
            'popular'  => $query->orderBy('downloads_count', 'desc'),
            'title'    => $query->orderBy('title', 'asc'),
            'level_id' => $query->orderBy('level_id', 'asc'),
            default    => $query->orderBy('created_at', 'desc'),
        };

        $subjects = $query->paginate(15);

        return view('livewire.subjects.index', [
            'subjects' => $subjects,
        ]);
    }
};
?>