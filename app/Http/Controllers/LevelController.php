<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LevelController extends Controller
{

    /**
     * Liste des articles pour l'admin
     */
    public function adminIndex(Request $request)
    {
        // $this->authorize('manage', Article::class);
        Auth::user()->hasRole([ 'admin', 'author']);

        $query = null;

        // Filtres
        if ($request->filled('school')) {
            $query = Level::where('school', $request->school)
                ->paginate(10);
        }
        
        if ($request->filled('system')) {
            $query = Level::where('system', $request->system)
                ->paginate(10);
        }

        if (!($request->filled('school')) && !($request->filled('system'))) {
            $query = Level::paginate(10);
        }

        $systems = Level::distinct()->pluck('system');
        $schools = Level::distinct()->pluck('school');

        $levels = $query;

        return view('admin.levels.index', compact( 'levels', 'systems', 'schools'));
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
        
        $systems = Level::distinct()->pluck('system');
        $schools = Level::distinct()->pluck('school');
        $levels = Level::all();
        return view('admin.levels.create', compact('levels', 'systems', 'schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Auth::user()->hasRole([ 'admin', 'author']);

        $request->validate([
            'name' => 'required|max:255',
            'school' => 'required|max:255',
            'system' => 'required|max:255',
            'description' => 'nullable|max:500',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $level = Level::create($data);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Classe créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        Auth::user()->hasRole([ 'admin', 'author']);
        
        $systems = Level::distinct()->pluck('system');
        $schools = Level::distinct()->pluck('school');
        return view('admin.levels.edit', compact('level', 'systems', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        Auth::user()->hasRole([ 'admin', 'author']);

        $request->validate([
            'name' => 'required|max:255',
            'school' => 'required|max:255',
            'system' => 'required|max:255',
            'description' => 'nullable|max:500',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        $level ->update($data);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Classe mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        //
    }

    /**
     * Publier/dépublier une classe
     */
    public function publish(Level $level)
    {
        // dd($level);
        Auth::user()->hasRole([ 'admin', 'author']);
        $level->update([
            'is_active' => $level->is_active ? 0 : 1,
            'updated_at' => now()
        ]);

        $status = $level->is_active ? 'Activéee' : 'désactivéee';
        return redirect()->back()->with('success', "Classe {$status} avec succès.");
    }
}
