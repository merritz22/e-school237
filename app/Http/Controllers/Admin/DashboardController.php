<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\UserLike;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;
use App\Models\ForumTopic;
use App\Models\DownloadLog;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tableau de bord administrateur
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'online' => User::where('last_login', '>=', now()->subMinutes(15))->count(),
            
            'total_articles' => Article::count(),
            'pending_articles' => Article::where('status', 'draft')->count(),
            'total_subjects' => EvaluationSubject::count(),
            'total_resources' => EducationalResource::count(),
            'blog_posts' => ForumTopic::count(),
            
            'activity' => [
                'total_downloads' => DownloadLog::count(),
                'downloads_today' => DownloadLog::whereDate('created_at', today())->count(),
                'downloads_this_month' => DownloadLog::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'comments' => ForumReply::count(),
                'comments_this_month' => ForumReply::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ],
        ];

        // Activités récentes
        $recent_activities = $this->getRecentActivities();

        // Contenu populaire
        $popular_content = [
            'articles' => Article::published()
                ->orderBy('views_count', 'desc')
                ->take(5)
                ->get(),
            'subjects' => EvaluationSubject::orderBy('downloads_count', 'desc')
                ->take(5)
                ->get(),
            'supports' => EducationalResource::orderBy('downloads_count', 'desc')
                ->take(5)
                ->get(),
            // 'blog_posts' => ForumTopic::approvedReplies()
            //     ->withCount('likes')
            //     ->orderBy('likes_count', 'desc')
            //     ->take(5)
            //     ->get(),
        ];

        // Utilisateurs les plus actifs
        $active_users = ['Empty'];
        // $active_users = User::withCount(['blogPosts', 'downloads'])
        //     ->orderBy('blog_posts_count', 'desc')
        //     ->orderBy('downloads_count', 'desc')
        //     ->take(10)
        //     ->get();

        // Données pour les graphiques
        $charts_data = [
            'users_growth' => $this->getUsersGrowthData(),
            'downloads_monthly' => $this->getDownloadsMonthlyData(),
            'content_distribution' => $this->getContentDistributionData(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recent_activities',
            'popular_content',
            'active_users',
            'charts_data'
        ));
    }

    /**
     * API pour les statistiques détaillées
     */
    public function stats(Request $request)
    {
        $period = $request->get('period', '30'); // 7, 30, 90, 365 jours 

        $stats = [
            'users' => $this->getUserStats($period),
            'content' => $this->getContentStats($period),
            'downloads' => $this->getDownloadStats($period),
            'engagement' => $this->getEngagementStats($period),
            'total' => Article::count(),
            'published' => Article::where('status', 'published')
                    // ->whereNotNull('published_at')
                    // ->where('published_at', '<=', now())
                    ->count(),
            'drafts' => Article::where('status', 'draft')
                    // ->whereNotNull('published_at')
                    // ->where('published_at', '<=', now())
                    ->count(),
            'total_views' => Article::whereNotNull('views_count')->count(),
            'popular_articles' => [],
            'recent_articles' => [],
            'categories_stats' => [],
        ];

        return view('admin.stats', compact(
            'stats'
        ));
    }

    /**
     * Activités récentes
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Nouveaux utilisateurs
        $new_users = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user_registered',
                    'user' => $user->name,
                    'created_at' => $user->created_at,
                    'icon' => 'user-plus',
                    'description' => 'Nouvel utilisateur inscrit',
                    'title' => 'Nouvel utilisateur: '. $user->name,
                ];
            });

        // Nouveaux téléchargements
        $recent_downloads = DownloadLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($download) {
                return [
                    'type' => 'download',
                    'user' => $download->user->name,
                    'created_at' => $download->created_at,
                    'icon' => 'download',
                    'description' => 'Téléchargement de ' . ($download->downloadable->title ?? 'Fichier supprimé'),
                    'title' => 'Téchargement de fichier',
                ];
            });

        // Nouveaux posts de blog
        $recent_posts = ForumTopic::with('author')
            // ->where('is_published', true)
            // ->orderBy('published_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($post) {
                return [
                    'type' => 'blog_post',
                    'user' => $post->author->name,
                    'created_at' => $post->published_at,
                    'icon' => 'edit',
                    'description' => 'Nouveau post: ' . $post->title,
                    'title' => 'commentaire',
                ];
            });

        return $activities->merge($new_users)
            ->merge($recent_downloads)
            ->merge($recent_posts)
            ->sortByDesc('created_at')
            ->take(15);
    }

    /**
     * Données de croissance des utilisateurs (12 derniers mois)
     */
    private function getUsersGrowthData()
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'users' => $count,
            ];
        }

        return $data;
    }

    /**
     * Données des téléchargements mensuels (12 derniers mois)
     */
    private function getDownloadsMonthlyData()
    {
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = DownloadLog::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'downloads' => $count,
            ];
        }

        return $data;
    }

    /**
     * Distribution du contenu
     */
    private function getContentDistributionData()
    {
        return [
            ['name' => 'Articles', 'value' => Article::count()],
            ['name' => 'Sujets', 'value' => EvaluationSubject::count()],
            ['name' => 'EducationalResources', 'value' => EducationalResource::count()],
            ['name' => 'Posts Blog', 'value' => ForumTopic::count()],
        ];
    }

    /**
     * Statistiques utilisateurs par période
     */
    private function getUserStats($period)
    {
        $date = now()->subDays($period);

        return [
            'new_users' => User::where('created_at', '>=', $date)->count(),
            'active_users' => User::where('last_login', '>=', $date)->count(),
            'total_users' => User::count(),
        ];
    }

    /**
     * Statistiques de contenu par période
     */
    private function getContentStats($period)
    {
        $date = now()->subDays($period);

        return [
            'new_articles' => Article::where('created_at', '>=', $date)->count(),
            'new_subjects' => EvaluationSubject::where('created_at', '>=', $date)->count(),
            'new_supports' => EducationalResource::where('created_at', '>=', $date)->count(),
            'new_posts' => ForumTopic::where('created_at', '>=', $date)->count(),
        ];
    }

    /**
     * Statistiques de téléchargement par période
     */
    private function getDownloadStats($period)
    {
        $date = now()->subDays($period);

        return [
            'total_downloads' => DownloadLog::where('created_at', '>=', $date)->count(),
            'unique_users' => DownloadLog::where('created_at', '>=', $date)
                ->distinct('user_id')
                ->count('user_id'),
            'avg_per_day' => DownloadLog::where('created_at', '>=', $date)->count() / $period,
        ];
    }

    /**
     * Statistiques d'engagement par période
     */
    private function getEngagementStats($period)
    {
        $date = now()->subDays($period);

        return [
            'new_comments' => ForumReply::where('created_at', '>=', $date)->count(),
            'new_likes' => UserLike::where('created_at', '>=', $date)->count(),
            'posts_published' => Article::where('published_at', '>=', $date)->count(),
        ];
    }
}