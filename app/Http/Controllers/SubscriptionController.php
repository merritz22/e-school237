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

        $subscriptions = Subscription::latest()->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }/**
     * Display a listing of the resource.
     */
    public function userIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour réaliser cette opération.');
        }

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
        // dd($request);
        if (!auth()->check()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        try {
            $request->validate([
                'level' => 'required|exists:levels,id|min:1',
                // 'subjects' => 'required|array|min:1',
                // 'levels.*' => 'exists:levels,id',
                'phone' => 'required|min:1',
                'price' => 'required|int|min:1',
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

        $exists = Subscription::where('user_id', $userId)
            ->where('level_id', $request->level)
            ->where('starts_at', $startsAt)
            ->exists();

        if ($exists) {

            return response()->json([
                'error' => false,
                'message' => 'Abonnement déjà existant !'
            ], 422);
        }

        Subscription::create([
            'user_id'    => $userId,
            'level_id' => $request->level,
            'subject_id' => null,
            'starts_at'  => $startsAt,
            'ends_at'    => $endsAt,
            'status'     => 'pending',
            'amount'     => $request->price,
            'currency'   => $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Abonnement créé avec succès'
            // 'subscription_ids' => 
        ]);
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
     * Activer un abonnement
     */
    public function publish(Subscription $subscription)
    {
        // dd($topic);
        Auth::user()->hasRole([ 'admin', 'author']);
        if ($subscription->status !== 'active') {
            $subscription->update([
                'status' => 'active',
                'updated_at' => now()
            ]);
        }

        $status = 'Activé';
        return redirect()->back()->with('success', "Abonnement {$status} avec succès.");
    }
}
