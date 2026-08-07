<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\DownloadLog;
use App\Models\EducationalResource;
use App\Models\EvaluationSubject;
use App\Models\Level;
use App\Models\Subscription;
use App\Models\User;
use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Rapport de revenus / abonnements.
     */
    public function revenue(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $baseQuery = fn () => Subscription::where('subscriptions.status', SubscriptionStatus::Active->value)
            ->whereBetween(DB::raw('COALESCE(subscriptions.validated_at, subscriptions.updated_at)'), [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);

        $total = (clone $baseQuery())->sum('amount');
        $count = (clone $baseQuery())->count();

        $byType = (clone $baseQuery())
            ->select('type', DB::raw('COUNT(*) as nb'), DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        $byLevel = (clone $baseQuery())
            ->join('levels', 'levels.id', '=', 'subscriptions.level_id')
            ->select('levels.name as level_name', DB::raw('COUNT(*) as nb'), DB::raw('SUM(subscriptions.amount) as total'))
            ->groupBy('levels.name')
            ->orderByDesc('total')
            ->get();

        $monthly = $this->monthlyBuckets($from, $to, function (Carbon $start, Carbon $end) {
            return Subscription::where('subscriptions.status', SubscriptionStatus::Active->value)
                ->whereBetween(DB::raw('COALESCE(subscriptions.validated_at, subscriptions.updated_at)'), [$start, $end])
                ->sum('amount');
        });

        return view('admin.reports.revenue', compact('total', 'count', 'byType', 'byLevel', 'monthly', 'from', 'to'));
    }

    public function exportRevenue(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $subscriptions = Subscription::with(['user', 'level'])
            ->where('subscriptions.status', SubscriptionStatus::Active->value)
            ->whereBetween(DB::raw('COALESCE(subscriptions.validated_at, subscriptions.updated_at)'), [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->orderBy('validated_at')
            ->get();

        $currency = config('subscriptions.currency');

        return $this->streamCsv('rapport-revenus', ['Date de validation', 'Utilisateur', 'Email', 'Niveau', 'Type', "Montant ({$currency})"], $subscriptions->map(fn ($s) => [
            optional($s->validated_at ?? $s->updated_at)->format('d/m/Y H:i'),
            $s->user->name ?? '—',
            $s->user->email ?? '—',
            $s->level->name ?? '—',
            $s->type,
            $s->amount,
        ]));
    }

    /**
     * Rapport de croissance des utilisateurs.
     */
    public function growth(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $newUsers = User::whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->count();
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();

        $profileCompleteCount = User::whereHas('information', function ($q) {
            $q->whereNotNull('establishment')
                ->whereNotNull('birth_date')
                ->whereNotNull('gender')
                ->whereNotNull('profession_id')
                ->whereNotNull('current_level_id');
        })->whereNotNull('whatsapp')->count();

        $byLevel = User::join('user_informations', 'user_informations.user_id', '=', 'users.id')
            ->join('levels', 'levels.id', '=', 'user_informations.current_level_id')
            ->select('levels.name as level_name', DB::raw('COUNT(*) as nb'))
            ->groupBy('levels.name')
            ->orderByDesc('nb')
            ->get();

        $byRole = User::select('role', DB::raw('COUNT(*) as nb'))->groupBy('role')->get();

        $monthly = $this->monthlyBuckets($from, $to, function (Carbon $start, Carbon $end) {
            return User::whereBetween('created_at', [$start, $end])->count();
        });

        return view('admin.reports.growth', compact(
            'newUsers', 'totalUsers', 'activeUsers', 'profileCompleteCount', 'byLevel', 'byRole', 'monthly', 'from', 'to'
        ));
    }

    public function exportGrowth(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $users = User::with('information.currentLevel')
            ->whereBetween('created_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->orderBy('created_at')
            ->get();

        return $this->streamCsv('rapport-croissance', ['Date d\'inscription', 'Nom', 'Email', 'Rôle', 'Niveau', 'Profil complet'], $users->map(fn ($u) => [
            $u->created_at->format('d/m/Y H:i'),
            $u->name,
            $u->email,
            $u->role,
            $u->information?->currentLevel?->name ?? '—',
            $u->isProfileComplete() ? 'Oui' : 'Non',
        ]));
    }

    /**
     * Rapport d'engagement contenu.
     */
    public function engagement(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $totalDownloads = DownloadLog::whereBetween('downloaded_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])->count();
        $uniqueDownloaders = DownloadLog::whereBetween('downloaded_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->distinct('user_id')
            ->count('user_id');

        $topResources = EducationalResource::orderByDesc('downloads_count')->take(10)->get();
        $topSubjects = EvaluationSubject::orderByDesc('downloads_count')->take(10)->get();
        $topArticles = Article::orderByDesc('views_count')->take(10)->get();

        // Requêtes brutes (pas Eloquent) : on ne veut que is_free/nb, pas des modèles
        // complets dont les accesseurs $appends (ex. download_url) appelleraient route()
        // sans clé valide dès que la vue sérialise ces lignes en JSON.
        $resourcesFreeVsPremium = DB::table('educational_resources')->select('is_free', DB::raw('COUNT(*) as nb'))->groupBy('is_free')->get();
        $subjectsFreeVsPremium = DB::table('evaluation_subjects')->select('is_free', DB::raw('COUNT(*) as nb'))->groupBy('is_free')->get();

        $monthly = $this->monthlyBuckets($from, $to, function (Carbon $start, Carbon $end) {
            return DownloadLog::whereBetween('downloaded_at', [$start, $end])->count();
        });

        return view('admin.reports.engagement', compact(
            'totalDownloads', 'uniqueDownloaders', 'topResources', 'topSubjects', 'topArticles',
            'resourcesFreeVsPremium', 'subjectsFreeVsPremium', 'monthly', 'from', 'to'
        ));
    }

    public function exportEngagement(Request $request)
    {
        [$from, $to] = $this->resolveRange($request);

        $downloads = DownloadLog::with('user')
            ->whereBetween('downloaded_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->orderBy('downloaded_at')
            ->get();

        return $this->streamCsv('rapport-engagement', ['Date', 'Utilisateur', 'Type de contenu', 'ID contenu', 'IP'], $downloads->map(fn ($d) => [
            $d->downloaded_at?->format('d/m/Y H:i'),
            $d->user->name ?? 'Anonyme',
            $d->resource_type,
            $d->resource_id,
            $d->ip_address,
        ]));
    }

    /**
     * Résout la période demandée (par défaut : les 12 derniers mois).
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveRange(Request $request): array
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))
            : now()->subMonths(11)->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))
            : now();

        return [$from, $to];
    }

    /**
     * Découpe une période en buckets mensuels et applique le callback sur chacun.
     */
    private function monthlyBuckets(Carbon $from, Carbon $to, \Closure $callback): array
    {
        $buckets = [];
        $cursor = $from->copy()->startOfMonth();
        $end = $to->copy()->endOfMonth();

        while ($cursor->lte($end)) {
            $start = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();

            $buckets[] = [
                'label' => $cursor->translatedFormat('M Y'),
                'value' => $callback($start, $monthEnd),
            ];

            $cursor->addMonth();
        }

        return $buckets;
    }

    private function streamCsv(string $baseName, array $headers, $rows)
    {
        $filename = $baseName . '-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
            fputcsv($out, $headers, ';');
            foreach ($rows as $row) {
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
