<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AppSettings;
use App\Models\Subscription;
use App\Models\DownloadLog;
use Carbon\Carbon;

class CanDownload
{
    public function handle(Request $request, Closure $next): Response
    {

        // 1. Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Vous devez être connecté pour accéder à cette ressource.');
        }

        $user = Auth::user();

        
        // Limite journalière globale (tous abonnements confondus)
        $dailyLimit = (int) AppSettings::where('code', 'DAILY_DOWNLOAD_LIMIT')->value('value');
        
        // Téléchargements du jour pour cet utilisateur
        $dailyDownloads = DownloadLog::where('user_id', $user->id)
        ->whereDate('downloaded_at', Carbon::today())
        ->count();
        
        // Limite journalière atteinte (avant même de vérifier l'abonnement)
        if ($dailyDownloads >= $dailyLimit) {
            
            return redirect()->route('download.limit', [
                'reason' => 'daily',
                ]);
                
        }
                
        // Vérifier si l'utilisateur dispose d'au moins 1 abonnement actif
        $hasSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        if (!$hasSubscription) {
            // Limite gratuite : 5 téléchargements par jour
            $freeLimit = (int) AppSettings::where('code', 'FREE_DOWNLOAD_LIMIT')->value('value') ?: 5;

            $freeDownloads = DownloadLog::where('user_id', $user->id)
                ->whereDate('downloaded_at', Carbon::today())
                ->count();

            if ($freeDownloads >= $freeLimit) {
                return redirect()->route('download.limit', [
                    'reason' => 'free_download_limit',
                ]);
            }

            // Sous la limite gratuite → on laisse passer, pas besoin de vérifier le niveau
            return $next($request);

        }

        // Récupérer subject ou resource depuis la route
        $subject  = $request->route('subject');

        $resourceParam = $request->route('resource');

        // Si c'est déjà un modèle (binding résolu), on l'utilise, sinon on le charge
        $resource = $resourceParam instanceof \App\Models\EducationalResource
            ? $resourceParam
            : \App\Models\EducationalResource::find($resourceParam);

        if (!$subject && !$resource) {
            abort(404, 'Ressource introuvable.');
        }

        $levelId = $subject?->level_id ?? $resource?->level_id;

        // Abonnement actif correspondant au niveau du contenu
        $subscription = Subscription::where('user_id', $user->id)
            ->where('level_id', $levelId)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            // L'utilisateur a un abonnement actif, mais pas pour ce niveau
            return redirect()->route('download.limit', [
                'reason' => 'wrong_level',
            ]);
        }

        // Vérifier la limite mensuelle (sauf pour ADVANCED)
        if ($subscription->type !== 'ADVANCED') {
            $limitCode = match ($subscription->type) {
                'CLASSIC'  => 'MONTHLY_DOWNLOAD_LIMIT_P1',
                'PREMIUM'  => 'MONTHLY_DOWNLOAD_LIMIT_P2',
                default    => null,
            };

            if ($limitCode) {
                $monthlyLimit = (int) AppSettings::where('code', $limitCode)->value('value');

                $monthlyDownloads = DownloadLog::where('user_id', $user->id)
                    ->whereMonth('downloaded_at', Carbon::now()->month)
                    ->whereYear('downloaded_at', Carbon::now()->year)
                    ->count();

                if ($monthlyDownloads >= $monthlyLimit) {
                    return redirect()->route('download.limit', [
                        'reason' => 'monthly',
                    ]);
                }
            }
        }


        return $next($request);
    }
}