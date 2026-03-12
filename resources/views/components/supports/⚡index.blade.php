<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EducationalResource;
use App\Models\Subject;
use App\Models\Level;

new class extends Component
{
    use WithPagination;

    public $subject_id = '';
    public $level_id = '';
    public $search = '';

    protected $queryString = [
        'subject_id' => ['except' => ''],
        'level_id' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function updatingSubjectId()
    {
        $this->resetPage();
    }

    public function updatingLevelId()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = EducationalResource::with('subject','level')
            ->where('is_approved',1);
        
        // 🔎 recherche par titre
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->subject_id) {
            $query->whereHas('subject', function ($q) {
                $q->where('slug', $this->subject_id);
            });
        }

        if ($this->level_id) {
            $query->whereHas('level', function ($q) {
                $q->where('slug', $this->level_id);
            });
        }

        return view('livewire.supports.index', [
            'resources' => $query->latest()->paginate(15),
            'subjects' => Subject::where('is_active',1)->get(),
            'levels' => Level::where('is_active',1)->get(),
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['subject_id','level_id']);
    }
};
?>
