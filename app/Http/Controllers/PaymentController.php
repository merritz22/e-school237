<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Providers\MtnTokenService;
use App\Jobs\CheckMtnPaymentStatus;
use App\Services\AuditLogger;
use App\Enums\SubscriptionStatus;

class PaymentController extends Controller
{
    /**
     * Lancer le paiement
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'payment_method' => 'required|in:mtn,orange',
        ]);

        // Seul MTN dispose d'une initiation programmatique dans ce contrôleur ;
        // Orange n'est traité que côté webhook (voir callback()). On l'indique
        // explicitement plutôt que de silencieusement traiter un choix "orange"
        // via le flux MTN.
        if ($request->payment_method !== 'mtn') {
            return response()->json(['error' => "Ce mode de paiement n'est pas encore disponible depuis cette interface."], 422);
        }

        $subscriptions = Subscription::where('user_id', auth()->id())
            ->where('status', SubscriptionStatus::Pending->value)
            ->get();

        if ($subscriptions->isEmpty()) {
            return response()->json(['error' => 'Aucun abonnement en attente'], 400);
        }

        $totalAmount = $subscriptions->sum('amount');

        $payment = Payment::create([
            'user_id' => auth()->id(),
            'amount' => $totalAmount,
            'currency' => config('subscriptions.currency'),
            'provider' => $request->payment_method,
            'status' => 'pending',
            'transaction_id' => (string) Str::uuid(),
        ]);

        $this->mtnPay($payment, $request->phone);

        // CheckMtnPaymentStatus::dispatch($payment)->onQueue('mtn')->delay(now()->addSeconds(30));

        // Mise à jour du status du paiement + activation de l'abonement
        $this->updateStatus($payment);

        return response()->json([
            'message' => 'Paiement MTN initié',
            'payment_id' => $payment->id
        ]);
    }

    public function updateStatus(Payment $payment)
    {
        // Mise à jour du paiement => Paiement reussit
        $payment->update([
            'status' => 'success',
            'payload' => '',//Le JSON renvoyé dans le callback doit être placé ici
        ]);

        // Mise à jour des soubscritption de l'utilisateur
        $subscriptions = Subscription::where('user_id', $payment->user_id)
            ->where('status', SubscriptionStatus::Pending->value)
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->update([
                'status' => SubscriptionStatus::Active->value,
                'validated_at' => now(),
            ]);

            AuditLogger::log(
                'subscription.auto_activated',
                "Activation automatique de l'abonnement #{$subscription->id} suite au paiement MTN #{$payment->id}",
                $subscription,
                ['status' => SubscriptionStatus::Pending->value],
                ['status' => SubscriptionStatus::Active->value],
                userId: null
            );
        }
    }

    public function mtnPay(Payment $payment, string $phone)
    {
        $token = MtnTokenService::getToken();

        // return response()->json([
        //     'token' => $token
        // ]);
        // $referenceId = (string) Str::uuid();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'X-Reference-Id' => $payment->transaction_id,
            'X-Target-Environment' => config('services.mtn.env'),
            'Ocp-Apim-Subscription-Key' => config('services.mtn.subscription_key'),
            'Content-Type' => 'application/json',
        ])->post(config('services.mtn.base_url') . '/collection/v1_0/requesttopay', [
            'amount' => (string) $payment->amount,
            // Le sandbox MTN MoMo n'accepte que EUR quel que soit le pays cible ;
            // en production on envoie la vraie devise du paiement (XAF).
            'currency' => config('services.mtn.env') === 'sandbox' ? 'EUR' : $payment->currency,
            'externalId' => $payment->id,
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $phone
            ],
            'payerMessage' => 'Paiement abonnement EduShare',
            'payeeNote' => 'Abonnement EduShare'
        ]);

        if ($response->failed()) {
            throw new \Exception($response->body());
        }
    }


    public function callback(Request $request)
    {
        // ⚡ Sécurité 1 : Vérifier signature (si fournie par Orange)
        $signature = $request->header('X-Callback-Signature');
        $expected = hash_hmac('sha256', $request->getContent(), config('services.orange.secret'));

        if ($signature !== $expected) {
            return response()->json(['error' => 'Signature invalide'], 403);
        }

        // ⚡ Sécurité 2 : Vérifier IP (si une liste a été configurée)
        $allowedIps = config('subscriptions.orange_webhook_ips');
        if (empty($allowedIps)) {
            Log::warning('Callback Orange reçu sans liste ORANGE_WEBHOOK_IPS configurée — contrôle IP ignoré.');
        } elseif (!in_array($request->ip(), $allowedIps, true)) {
            return response()->json(['error' => 'IP non autorisée'], 403);
        }

        // Chercher le paiement
        $payment = Payment::where('id', $request->externalId)->firstOrFail();

        if ($request->status === 'SUCCESS') {
            $payment->update([
                'status' => 'success',
                'transaction_id' => $request->transactionId,
            ]);

            $oldStatus = $payment->subscription->status;
            $payment->subscription->update([
                'status' => SubscriptionStatus::Active->value,
                'validated_at' => now(),
            ]);

            AuditLogger::log(
                'subscription.auto_activated',
                "Activation automatique de l'abonnement #{$payment->subscription->id} suite au paiement Orange #{$payment->id}",
                $payment->subscription,
                ['status' => $oldStatus],
                ['status' => SubscriptionStatus::Active->value],
                userId: null
            );

        } else {
            $payment->update(['status' => 'failed']);

            $oldStatus = $payment->subscription->status;
            $payment->subscription->update(['status' => SubscriptionStatus::Cancelled->value]);

            AuditLogger::log(
                'subscription.auto_cancelled',
                "Échec du paiement Orange #{$payment->id} — abonnement #{$payment->subscription->id} annulé",
                $payment->subscription,
                ['status' => $oldStatus],
                ['status' => SubscriptionStatus::Cancelled->value],
                userId: null
            );
        }

        return response()->json(['message' => 'Callback traité']);
    }


}
