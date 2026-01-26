<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubjectController extends Controller
{

    /**
     * Liste des articles pour l'admin
     */
    public function adminIndex(Request $request)
    {
        // $this->authorize('manage', Article::class);
        Auth::user()->hasRole([ 'admin', 'author']);

        $topics = Subject::paginate(10);

        return view('admin.topics.index', compact( 'topics'));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Auth::user()->hasRole([ 'admin', 'author']);
        
        $subjects = Subject::all();
        return view('admin.topics.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Auth::user()->hasRole([ 'admin', 'author']);


        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $topic = Subject::create($data);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Matière créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $topic)
    {
        Auth::user()->hasRole([ 'admin', 'author']);
        
        return view('admin.topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $topic)
    {
        Auth::user()->hasRole([ 'admin', 'author']);


        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|max:500',
        ]);
        
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $topic->update($data);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Matière mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }

    /**
     * Publier/dépublier une matière
     */
    public function publish(Subject $topic)
    {
        // dd($topic);
        Auth::user()->hasRole([ 'admin', 'author']);
        $topic->update([
            'is_active' => $topic->is_active ? 0 : 1,
            'updated_at' => now()
        ]);

        $status = $topic->is_active ? 'Activéee' : 'désactivéee';
        return redirect()->back()->with('success', "Matière {$status} avec succès.");
    }
}
