<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EvaluationSubject;
use App\Models\Subject;
use App\Models\Level;

new class extends Component
{
    use WithPagination;

    public $subject_id = '';
    public $level_id = '';
    public $type = '';
    public $year = '';
    public $sort = 'latest';
    public $search = '';

    // Données pour les filtres
    public $filter_subjects = [];
    public $levels = [];
    public $types = [];
    public $years = [];

    protected $queryString = [
        'subject_id' => ['except' => ''],
        'level_id' => ['except' => ''],
        'type' => ['except' => ''],
        'year' => ['except' => ''],
        'sort' => ['except' => 'latest'],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->filter_subjects = Subject::where('is_active', 1)->get();
        $this->levels = Level::where('is_active', 1)->get();
        $this->types = EvaluationSubject::distinct()->pluck('type')->filter()->sort();
        $this->years = EvaluationSubject::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
    }

    public function updating($property)
    {
        // Reset pagination on filter update
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->subject_id = '';
        $this->level_id = '';
        $this->type = '';
        $this->year = '';
        $this->sort = 'latest';
        $this->$search = '';
    }

    public function render()
    {
        $query = EvaluationSubject::with('subject', 'level');

        // 🔎 recherche par titre
        if ($this->search) $query->where('title', 'like', '%' . $this->search . '%');
        if ($this->subject_id) $query->where('subject_id', $this->subject_id);
        if ($this->level_id) $query->where('level_id', $this->level_id);
        if ($this->type) $query->where('type', $this->type);
        if ($this->year) $query->whereYear('created_at', $this->year);

        switch ($this->sort) {
            case 'popular':
                $query->orderBy('downloads_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'level_id':
                $query->orderBy('level_id', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $subjects = $query->paginate(15);

        return view('livewire.subjects.index', [
            'subjects' => $subjects
        ]);
    }
};
?>
