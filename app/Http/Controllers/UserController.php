<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DownloadLog;
use App\Models\Subscription;
use App\Models\ForumTopic;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Dashboard utilisateur
     */
    public function dashboard()
    {
        $user = Auth::user();

        $data = [
            // Statistiques personnelles
            'stats' => [
                'downloads_count' => DownloadLog::where('user_id', $user->id)->count(),
                'blog_posts_count' => ForumTopic::where('author_id', $user->id)->count(),
                'published_posts_count' => ForumTopic::where('author_id', $user->id)->count(),//->where('is_published', true)
                'articles_count' => $user->hasRole(['admin', 'author']) ? Article::where('author_id', $user->id)->count() : 0,
            ],

            // Activités récentes
            'recent_downloads' => DownloadLog::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),

            'recent_posts' => [],//:ForumTopic::where('author_id', $user->id)
                // ->orderBy('created_at', 'desc')
                // ->take(5)
                // ->get(),

            // Posts populaires de l'utilisateur
            'popular_posts' => [],
        //     'popular_posts' => ForumTopic::where('author_id', $user->id)
        //          ->where('is_published', true)
        //         ->withCount('likes')
        //         ->orderBy('likes_count', 'desc')
        //         ->take(3)
        //         ->get(),
        ];

        return view('user.dashboard', $data);
    }

    /**
     * Profil utilisateur
     */
    public function profile()
    {
        $user = Auth::user();
        $userSubscripritions = Subscription::where('user_id',$user->id)->paginate(15);
        // dd($userSubscripritions);
        return view('user.profile', compact('user','userSubscripritions'));
    }

    /**
     * Met à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'bio', 'website', 'location']);

        // Gestion de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Met à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Historique des téléchargements
     */
    public function downloads(Request $request)
    {
        $user = Auth::user();

        $query = DownloadLog::where('user_id', $user->id);

        // Filtrage par type
        if ($request->filled('type')) {
            $query->where('downloadable_type', 'App\\Models\\' . ucfirst($request->type));
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $downloads = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistiques des téléchargements
        $stats = [
            'total' => DownloadLog::where('user_id', $user->id)->count(),
            'this_month' => DownloadLog::where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'subjects' => DownloadLog::where('user_id', $user->id)
                ->where('downloadable_type', 'App\\Models\\Subject')
                ->count(),
            'supports' => DownloadLog::where('user_id', $user->id)
                ->where('downloadable_type', 'App\\Models\\Support')
                ->count(),
        ];

        return view('user.downloads', compact('downloads', 'stats'));
    }

    /**
     * Posts de blog de l'utilisateur
     */
    public function posts(Request $request)
    {
        $user = Auth::user();

        $query = ForumTopic::where('author_id', $user->id);

        // Filtrage par statut
        // if ($request->filled('status')) {
        //     $query->where('is_published', $request->status === 'published');
        // }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistiques des posts
        $stats = [
            'total' => ForumTopic::where('author_id', $user->id)->count(),
        'published' => [],//ForumTopic::where('user_id', $user->id)->where('is_published', true)->count(),
            'drafts' => [],//ForumTopic::where('user_id', $user->id)->where('is_published', false)->count(),
            'total_likes' => ForumTopic::where('author_id', $user->id)
                // ->withCount('likes')
                ->get()
                ->sum('likes_count'),
        ];

        return view('user.posts', compact('posts', 'stats'));
    }

    /**
     * Historique d'activité
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        // Activités récentes
        $activities = collect();

        // Téléchargements récents
        $recent_downloads = DownloadLog::with('downloadable')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($download) {
                return [
                    'type' => 'download',
                    'action' => 'Téléchargement',
                    'item' => $download->downloadable->title ?? 'Fichier supprimé',
                    'date' => $download->created_at,
                    'icon' => 'download',
                ];
            });

        // Posts récents
        $recent_posts = ForumTopic::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($post) {
                return [
                    'type' => 'blog_post',
                    'action' => '',//$post->is_published ? 'Publication' : 'Brouillon',
                    'item' => $post->title,
                    'date' => $post->created_at,
                    'icon' => 'edit',
                    'url' => ''//$post->is_published ? route('blog.show', $post->slug) : null,
                ];
            });

        // Fusionner et trier les activités
        $activities = $recent_downloads->merge($recent_posts)
            ->sortByDesc('date')
            ->take(20);

        return view('user.history', compact('activities'));
    }
}