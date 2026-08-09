<?php

namespace App\Http\Controllers;

use App\Models\Training;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::published()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('trainings.index', compact('trainings'));
    }

    public function show(Training $training)
    {
        if (!$training->isPublished()) {
            abort(404);
        }

        $relatedTrainings = Training::published()
            ->where('id', '!=', $training->id)
            ->take(3)
            ->get();

        return view('trainings.show', compact('training', 'relatedTrainings'));
    }
}
