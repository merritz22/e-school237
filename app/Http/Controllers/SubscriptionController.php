<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Services\AuditLogger;
use App\Services\SubscriptionPricing;
use App\Enums\SubscriptionStatus;
use App\Models\Notification;
use App\Models\UserNotification;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour réaliser cette opération.');
        }

        $query = Subscription::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->paginate(15)->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }
    
    /**
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

        // Dates scolaires Cameroun
        [$startsAt, $endsAt] = SubscriptionPricing::schoolYearRange();

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
            'status'     => SubscriptionStatus::Pending->value,
            'amount'     => $request->price,
            'currency'   => config('subscriptions.currency'),
            'phone'      => $request->phone,
            'type'       => SubscriptionPricing::classify((float) $request->price)->value,
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
        if ($subscription->status !== SubscriptionStatus::Active->value) {
            $oldStatus = $subscription->status;

            $subscription->update([
                'status' => SubscriptionStatus::Active->value,
                'validated_at' => now(),
                'validated_by' => Auth::id(),
                'updated_at' => now()
            ]);

            AuditLogger::log(
                'subscription.validated',
                "Validation manuelle de l'abonnement #{$subscription->id} ({$subscription->amount} " . config('subscriptions.currency') . ") pour {$subscription->user->name}",
                $subscription,
                ['status' => $oldStatus],
                ['status' => SubscriptionStatus::Active->value]
            );
        }

        $notification = Notification::where('code', 'WAITING_PAYMENT')->first();

        $user_notif = UserNotification::where('notification_id', $notification->id)
            ->where('user_id', $subscription->user_id)
            ->first();

        if ($user_notif) {
            $user_notif->is_visible = 0;
            $user_notif->save();
        }

        // Création de la notification
        NotificationService::send(
            'SUBSCRIPTION_VALIDATED',
            $subscription->user,
            []
        );

        $status = 'Activé';
        return redirect()->back()->with('success', "Abonnement {$status} avec succès.");
    }

    /**
     * Supprimer un abonnement — uniquement s'il n'est pas actif.
     */
    public function destroy(Subscription $subscription)
    {
        if ($subscription->status === SubscriptionStatus::Active->value) {
            return redirect()->back()->with('error', "Impossible de supprimer un abonnement actif. Désactivez-le d'abord.");
        }

        AuditLogger::log(
            'subscription.deleted',
            "Suppression de l'abonnement #{$subscription->id} ({$subscription->status}) pour {$subscription->user?->name}",
            null,
            ['id' => $subscription->id, 'status' => $subscription->status, 'amount' => $subscription->amount, 'user_id' => $subscription->user_id],
            []
        );

        $subscription->delete();

        return redirect()->back()->with('success', 'Abonnement supprimé avec succès.');
    }
}
