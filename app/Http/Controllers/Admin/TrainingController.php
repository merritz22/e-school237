<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.trainings.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('trainings', 'public');
        }

        $data['slug'] = Str::slug($request->title);
        $data['created_by'] = Auth::id();

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $training = Training::create($data);

        AuditLogger::log(
            'training.created',
            "Création de la formation « {$training->title} »",
            $training,
            [],
            $training->only(['title', 'status', 'price'])
        );

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Formation créée avec succès.');
    }

    public function edit(Training $training)
    {
        return view('admin.trainings.edit', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        $data = $this->validateData($request);

        if ($request->title !== $training->title) {
            $data['slug'] = Str::slug($request->title);
        }

        if ($request->hasFile('banner')) {
            $training->deleteBanner();
            $data['banner'] = $request->file('banner')->store('trainings', 'public');
        }

        if ($data['status'] === 'published' && $training->status !== 'published') {
            $data['published_at'] = now();
        }

        $training->update($data);

        AuditLogger::log(
            'training.updated',
            "Mise à jour de la formation « {$training->title} »",
            $training,
            [],
            []
        );

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Formation mise à jour avec succès.');
    }

    public function destroy(Training $training)
    {
        $training->deleteBanner();

        AuditLogger::log(
            'training.deleted',
            "Suppression de la formation « {$training->title} »",
            null,
            ['id' => $training->id, 'title' => $training->title],
            []
        );

        $training->delete();

        return redirect()->route('admin.trainings.index')
            ->with('success', 'Formation supprimée avec succès.');
    }

    public function publish(Training $training)
    {
        $oldStatus = $training->status;

        $training->publish();

        AuditLogger::log(
            'training.status_changed',
            "Formation « {$training->title} » : {$oldStatus} → publié",
            $training,
            ['status' => $oldStatus],
            ['status' => 'published']
        );

        return redirect()->back()->with('success', 'Formation publiée avec succès.');
    }

    public function disable(Training $training)
    {
        $oldStatus = $training->status;

        $training->disable();

        AuditLogger::log(
            'training.status_changed',
            "Formation « {$training->title} » : {$oldStatus} → désactivé",
            $training,
            ['status' => $oldStatus],
            ['status' => 'disabled']
        );

        return redirect()->back()->with('success', 'Formation désactivée avec succès.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'banner' => 'nullable|image|max:2048',
            'duration' => 'required|max:100',
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|min:0|gt:price',
            'technical_prerequisites' => 'nullable|string',
            'intellectual_prerequisites' => 'nullable|string',
            'status' => 'required|in:draft,published,disabled',
        ]);
    }
}
