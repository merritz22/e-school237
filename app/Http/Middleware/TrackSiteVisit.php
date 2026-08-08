<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Enregistre une visite unique par visiteur (session) et par jour, pour
 * alimenter le rapport "Visites" de l'administration. Les admins ne sont
 * pas comptés, comme pour les autres rapports.
 */
class TrackSiteVisit
{
    const CACHE_TTL_MINUTES = 1440; // 24h

    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldTrack($request)) {
            $this->recordVisit($request);
        }

        return $next($request);
    }

    private function shouldTrack(Request $request): bool
    {
        if (!$request->isMethod('GET')) {
            return false;
        }

        if ($request->ajax() || $request->wantsJson() || $request->hasHeader('X-Livewire')) {
            return false;
        }

        if ($request->is('admin') || $request->is('admin/*')) {
            return false;
        }

        return !(Auth::check() && Auth::user()->role === 'admin');
    }

    private function recordVisit(Request $request): void
    {
        $visitorKey = session()->getId();
        $today = now()->toDateString();
        $cacheKey = "site_visit:{$today}:{$visitorKey}";

        if (Cache::has($cacheKey)) {
            return;
        }

        SiteVisit::firstOrCreate(
            ['visitor_key' => $visitorKey, 'visited_on' => $today],
            ['user_id' => Auth::id(), 'ip_address' => $request->ip()]
        );

        Cache::put($cacheKey, true, now()->addMinutes(self::CACHE_TTL_MINUTES));
    }
}
