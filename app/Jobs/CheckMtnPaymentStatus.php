<?php

namespace App\Jobs;


use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\MtnPaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Providers\MtnTokenService;
use Illuminate\Support\Facades\Http;

class CheckMtnPaymentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public function __construct(public Payment $payment) {}

    public function handle()
    {

        Log::info("Début du job CheckMtnPaymentStatus pour le paiement {$this->payment->id}");

        $token = MtnTokenService::getToken();

        // ensuite logique pour checkStatus
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Ocp-Apim-Subscription-Key' => config('services.mtn.subscription_key'),
            'X-Target-Environment' => config('services.mtn.env'),
            'Content-Type' => 'application/json',
        ])->get(config('services.mtn.base_url') . "/collection/v1_0/requesttopay/{$this->payment->transaction_id}");

        $data = $response->json();

        $status = $data['status'] ?? 'PENDING';

        Log::info("Résultat MTN : " . json_encode($result));

        match ($result['status']) {

            'SUCCESSFUL' => $this->handleSuccess($result),

            'FAILED', 'REJECTED' => $this->handleFailure($result),

            'PENDING' => $this->retryLater(),

            default => throw new \Exception('Statut MTN inconnu'),
        };

        Log::info("Fin du job pour le paiement {$this->payment->id}");
    }

    private function handleSuccess(array $result)
    {
        $this->payment->update([
            'status' => 'success',
            'provider_reference' => $result['financialTransactionId'] ?? null,
        ]);

        Subscription::where('user_id', $this->payment->user_id)
            ->where('status', 'pending')
            ->update(['status' => 'active']);
    }

    private function handleFailure(array $result)
    {
        $this->payment->update(['status' => 'failed']);

        Subscription::where('user_id', $this->payment->user_id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);
    }

    private function retryLater()
    {
        // ré-enqueue le job dans 30s
        self::dispatch($this->payment)->delay(now()->addSeconds(30));
    }
}

