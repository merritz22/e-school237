<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Subject;
use Illuminate\Http\Request;

class LevelSubjectController extends Controller
{
    public function index()
    {
        $levels = Level::withCount('subjects')->orderBy('name')->get();

        return view('admin.level-subjects.index', compact('levels'));
    }

    public function edit(Level $level)
    {
        $subjects        = Subject::orderBy('name')->get();
        $linkedSubjectIds = $level->subjects->pluck('id')->toArray();

        return view('admin.level-subjects.edit', compact('level', 'subjects', 'linkedSubjectIds'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'subject_ids'   => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $level->subjects()->sync($request->input('subject_ids', []));

        return redirect()
            ->route('admin.level-subjects.index')
            ->with('success', "Matières de « {$level->name} » mises à jour.");
    }
}