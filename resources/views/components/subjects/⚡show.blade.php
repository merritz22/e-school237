<?php

use Livewire\Component;
use App\Models\EvaluationSubject;
use Illuminate\Support\Collection;

new class extends Component
{
    public EvaluationSubject $subject;
    public Collection $relatedSubjects; // ✅ Collection typée

    public function mount(EvaluationSubject $subject)
    {
        $this->subject = $subject;

        // ✅ Même nom que dans la vue
        $this->relatedSubjects = EvaluationSubject::where('id', '!=', $subject->id)
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