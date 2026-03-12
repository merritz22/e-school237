<?php

use Livewire\Component;
use App\Models\EvaluationSubject;

new class extends Component
{
    public EvaluationSubject $subject;
    public $relatedSubjects = [];

    public function mount(EvaluationSubject $subject)
    {
        $this->subject = $subject;

        // Sujets similaires
        $this->related_subjects = EvaluationSubject::where('id', '!=', $subject->id)
            ->where(function ($query) use ($subject) {
                $query->where('level_id', $subject->level_id)
                      ->where('subject_id', $subject->subject_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.subjects.show');
    }
};
?>
