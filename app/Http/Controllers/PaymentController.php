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

        $subscriptions = Subscription::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get();

        if ($subscriptions->isEmpty()) {
            return response()->json(['error' => 'Aucun abonnement en attente'], 400);
        }

        $totalAmount = $subscriptions->sum('amount');

        $payment = Payment::create([
            'user_id' => auth()->id(),
            'amount' => $totalAmount,
            'currency' => 'XAF',
            'provider' => 'mtn',
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
        Subscription::where('user_id', $payment->user_id)
            ->where('status', 'pending')
            ->update(['status' => 'active']);
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
            'currency' => 'EUR',
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

        // ⚡ Sécurité 2 : Vérifier IP (optionnel mais recommandé)
        $allowedIps = ['154.66.XX.XX', '154.66.XX.XX']; // IPs officielles Orange
        if (!in_array($request->ip(), $allowedIps)) {
            return response()->json(['error' => 'IP non autorisée'], 403);
        }

        // Chercher le paiement
        $payment = Payment::where('id', $request->externalId)->firstOrFail();

        if ($request->status === 'SUCCESS') {
            $payment->update([
                'status' => 'success',
                'transaction_id' => $request->transactionId,
            ]);

            $payment->subscription->update([
                'status' => 'active'
            ]);

        } else {
            $payment->update(['status' => 'failed']);
            $payment->subscription->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'Callback traité']);
    }


}
