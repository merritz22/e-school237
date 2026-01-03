<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil
     */
    public function index()
    {
        // Utilisation du cache pour optimiser les performances
        $data = Cache::remember('home_data', 300, function () { // Cache pendant 10 minutes
            return [
                // Derniers articles (5 plus récents)
                'latest_articles' => Article::with('subject', 'author')
                    ->where('status','published')
                    ->latest()
                    ->take(5)
                    ->get(),

                // Derniers sujets d'évaluation (5 plus récents)
                'latest_subjects' => EvaluationSubject::latest()
                    ->take(5)
                    ->get(),

                // Derniers supports pédagogiques (5 plus récents)
                'latest_supports' => EducationalResource::latest()
                    ->where('is_approved', 1)
                    ->take(5)
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

        // Catégories pour le menu de navigation
        $categories = Cache::remember('home_categories', 3600, function () { // Cache pendant 1 heure
            return [
                'article_categories' => \App\Models\Subject::whereHas('subjects')
                ->withCount('subjects')
                ->orderBy('name')
                ->get(),
                'subject_categories' => \App\Models\Subject::whereHas('subjects')
                ->withCount('subjects')
                ->orderBy('name')
                ->get(),
                'support_categories' => \App\Models\Category::whereHas('supports')
                ->withCount('supports')
                ->orderBy('name')
                ->get(),
            ];
        });
        // dd($categories );

        return view('home', array_merge($data, $categories));
    }

    /**
     * Recherche générale sur le site
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        
        if (empty($query)) {
            return redirect()->route('home');
        }

        $results = [];

        // Recherche dans les articles
        if ($type === 'all' || $type === 'articles') {
            $results['articles'] = Article::published()
                ->with('category', 'author')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%")
                      ->orWhere('excerpt', 'LIKE', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'articles');
        }

        // Recherche dans les sujets
        if ($type === 'all' || $type === 'subjects') {
            $results['subjects'] = EvaluationSubject::with('category')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('level', 'LIKE', "%{$query}%")
                      ->orWhere('subject_name', 'LIKE', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'subjects');
        }

        // Recherche dans les supports
        if ($type === 'all' || $type === 'supports') {
            $results['supports'] = EducationalResource::with('category')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('file_type', 'LIKE', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'supports');
        }

        // Recherche dans les posts de blog
        if ($type === 'all' || $type === 'blog') {
            $results['blog_posts'] = ForumTopic::published()
                ->with('author')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'blog');
        }

        // Compter les résultats totaux
        $total_results = 0;
        foreach ($results as $result_type) {
            $total_results += $result_type->total();
        }

        return view('search.results', [
            'query' => $query,
            'type' => $type,
            'results' => $results,
            'total_results' => $total_results,
        ]);
    }
}