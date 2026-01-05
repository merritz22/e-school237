<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\Subject;
use App\Models\Level;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil
     */
    public function index()
    {
        $data = null;
        $latestArticles = Article::with('subject', 'author')
            ->where('status','published')
            ->latest()
            ->take(6)
            ->get();

        if (!Auth::check()) { //Visiteur sans compte
            // Utilisation du cache pour optimiser les performances
            $data = Cache::remember('home_data', 300, function () use ($latestArticles) { // Cache pendant 10 minutes
                return [
                    // Derniers articles (6 plus récents)
                    'latest_articles' => $latestArticles,

                    // Derniers sujets d'évaluation (6 plus récents)
                    'latest_subjects' => EvaluationSubject::latest()
                        ->where('is_free',1)
                        ->take(6)
                        ->get(),

                    // Derniers supports pédagogiques (6 plus récents)
                    'latest_supports' => EducationalResource::latest()
                        ->where('is_free',1)
                        ->where('is_approved', 1)
                        ->take(6)
                        ->get(),

                    // Derniers posts de blog (3 plus récents)
                    'latest_blog_posts' => ForumTopic::with('author')
                        // ->published()
                        ->latest()
                        ->take(3)
                        ->get(),

                    // Articles les plus populaires (par nombre de vues)
                    'popular_articles' => Article::with('subject', 'author')
                        ->where('status','published')
                        ->orderBy('views_count', 'desc')
                        ->take(3)
                        ->get(),

                    // Supports les plus téléchargés
                    'popular_supports' => EducationalResource::with('subject')
                        ->orderBy('downloads_count', 'desc')
                        ->where('is_approved', 1)
                        ->take(3)
                        ->get(),

                    // Statistiques générales
                    'stats' => [
                        'total_articles' => Article::where('status','published')->count(),
                        'total_subjects' => EvaluationSubject::count(),
                        'total_supports' => EducationalResource::where('is_approved', 1)->count(),
                        'total_users' => User::where('is_active', true)->count(),
                        'total_downloads' => EducationalResource::sum('downloads_count') + EvaluationSubject::sum('downloads_count'),
                    ],
                ];
            });
        }
        else
        {
            $userId = Auth::user()->id;
            //Visiteur avec un compte utilisateur, on liste les article gratuits + ceux de son abonement
            // Liste des sujets disponnibles dans mon abonement
            $subjectIds = Subject::whereHas('subscriptions', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereDate('ends_at', '>=', now())
                    ->where('status', 'active');
            })->pluck('id');

            // Liste des niveaux disponnibles dans mon abonement
            $levelIds = Level::whereHas('subscriptions', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereDate('ends_at', '>=', now())
                    ->where('status', 'active');
            })->pluck('id');

            $latestSubjects = EvaluationSubject::query()
                ->whereIn('subject_id', $subjectIds)
                ->whereIn('level_id', $levelIds)
                ->latest()
                ->take(6)
                ->get();

            $latestSupports = EducationalResource::query()
                ->whereIn('subject_id', $subjectIds)
                ->whereIn('level_id', $levelIds)
                ->latest()
                ->take(6)
                ->get();
            // dd($subjectIds, $levelIds, $latestSubjects);
            $data = Cache::remember('home_data_user_{$userId}', 300, function () use($latestArticles, $latestSubjects, $latestSupports) { // Cache pendant 10 minutes
                return [
                    // Derniers articles (6 plus récents)
                    'latest_articles' => $latestArticles,

                    // Derniers sujets d'évaluation (6 plus récents)
                    'latest_subjects' => $latestSubjects,

                    // Derniers supports pédagogiques (6 plus récents)
                    'latest_supports' => $latestSupports,

                    // Derniers posts de blog (3 plus récents)
                    'latest_blog_posts' => [],

                    // Articles les plus populaires (par nombre de vues)
                    'popular_articles' => [],

                    // Supports les plus téléchargés
                    'popular_supports' => [],

                    // Statistiques générales
                    'stats' => [
                        'total_articles' => Article::where('status','published')->count(),
                        'total_subjects' => EvaluationSubject::count(),
                        'total_supports' => EducationalResource::where('is_approved', 1)->count(),
                        'total_users' => User::where('is_active', true)->count(),
                        'total_downloads' => EducationalResource::sum('downloads_count') + EvaluationSubject::sum('downloads_count'),
                    ],
                ];
            });
        }
        

        // Catégories pour le menu de navigation
        $categories = Cache::remember('home_categories', 300, function () { // Cache pendant 5 min
            return [
                'article_categories' => \App\Models\Subject::whereHas('articles', function ($query){
                    $query->where('status', 'published');
                })->withCount('articles')
                ->orderBy('name')
                ->get(),
                'subject_categories' => \App\Models\Subject::whereHas('subjects')
                ->withCount('subjects')
                ->orderBy('name')
                ->get(),
                'support_categories' => \App\Models\Subject::whereHas('supports', function ($query){
                    $query->where('is_approved', 1);
                })->withCount('supports')
                ->orderBy('name')
                ->get(),
            ];
        });
        // dd($categories );

        return view('home', array_merge($data, $categories));
    }

}