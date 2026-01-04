<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour réaliser cette opération.');
        }

        $subjects = Subject::all()->where('is_active', 1);
        $levels = Level::all()->where('is_active', 1);
        return view('subscriptions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour réaliser cette opération.');
        }

        $subjects = Subject::all()->where('is_active', 1);
        $levels = Level::all()->where('is_active', 1);
        return view('subscriptions.create', compact('subjects', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        try {
            $request->validate([
                'levels' => 'required|array|min:1',
                'subjects' => 'required|array|min:1',
                'levels.*' => 'exists:levels,id',
                'subjects.*' => 'exists:subjects,id',
                'unit_price' => 'required|int|min:1',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

        $userId = auth()->id();

        //Dates scolaires Cameroun
        $startsAt = Carbon::create(date('Y'), 9, 1);
        $endsAt = Carbon::create(date('Y') + 1, 6, 30);

        DB::beginTransaction();
        try {
            foreach ($request->levels as $levelId) {
                foreach ($request->subjects as $subjectId) {

                    // ⛔ Empêcher les doublons
                    $exists = Subscription::where('user_id', $userId)
                        ->where('subject_id', $subjectId)
                        ->where('level_id', $levelId)
                        ->where('starts_at', $startsAt)
                        ->exists();

                    if ($exists) {
                        continue; // on ignore l'abonnement existant
                    }

                    Subscription::create([
                        'user_id'    => $userId,
                        'subject_id' => $subjectId,
                        'level_id' => $levelId,
                        'starts_at'  => $startsAt,
                        'ends_at'    => $endsAt,
                        'status'     => 'pending',
                        'amount'     => $request->unit_price,
                        'currency'   => 'XAF',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Abonnement créé avec succès'
                // 'subscription_ids' => 
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création des abonnements',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
