<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\Subject;

new class extends Component
{
    use WithPagination;

    public $showModal = false;

    public $subject = '';
    public $sort = 'latest';
    public $search = '';

    protected $queryString = [
        'subject',
        'sort',
        'search'
    ];

    public function updatingSubject()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Article::with(['subject','author'])
            ->where('status','published');
        
        // 🔎 recherche par titre
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // filtre matière
        if ($this->subject) {
            $query->whereHas('subject', function ($q) {
                $q->where('slug', $this->subject);
            });
        }

        // tri
        switch ($this->sort) {

            case 'popular':
                $query->orderBy('views_count','desc');
                break;

            case 'oldest':
                $query->orderBy('created_at','asc');
                break;

            case 'title':
                $query->orderBy('title','asc');
                break;

            default:
                $query->latest();
        }

        return view('livewire.articles.index', [
            'articles' => $query->paginate(15),
            'subjects' => Subject::where('is_active',1)->get(),
        ]);
    }
};
?>

