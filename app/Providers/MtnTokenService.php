<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MtnTokenService
{
    public static function getToken(): string
    {
        return Cache::remember('mtn_access_token', 3500, function () {

            $response = Http::withBasicAuth(
                config('services.mtn.api_user'),
                config('services.mtn.api_key')
            )
            ->withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.mtn.subscription_key'),
                'Content-Type' => 'application/json'
            ])
            ->post(config('services.mtn.base_url') . '/collection/token/');

            if ($response->failed()) {
                throw new \Exception('MTN Token error: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    public function checkStatus($payment)
    {
        $token = $this->getToken();

        // Génère le X-Reference-Id utilisé lors de la création du paiement
        $referenceId = $payment->transaction_id; // ou un champ que tu as sauvegardé

        // Requête MTN pour obtenir le statut
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Ocp-Apim-Subscription-Key' => config('services.mtn.subscription_key'),
            'X-Target-Environment' => config('services.mtn.env'),
            'Content-Type' => 'application/json',
        ])->get(config('services.mtn.base_url') . "/collection/v1_0/requesttopay/$referenceId");

        if ($response->failed()) {
            throw new \Exception('Erreur MTN checkStatus: ' . $response->body());
        }

        $data = $response->json();

        // Exemple de retour standardisé pour ton job
        return [
            'status' => $data['status'] ?? 'PENDING', // SUCCESSFUL, FAILED, PENDING
            'financialTransactionId' => $data['financialTransactionId'] ?? null,
            'payer' => $data['payer'] ?? null,
            'amount' => $data['amount'] ?? null,
        ];
    }

}
