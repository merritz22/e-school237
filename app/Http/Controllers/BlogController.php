<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Liste des posts de blog
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('author')->published();

        // Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('content', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->withCount('likes')->orderBy('likes_count', 'desc');
                break;
            case 'discussed':
                $query->withCount('comments')->orderBy('comments_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(10);

        // Posts populaires
        $popular_posts = BlogPost::published()
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(5)
            ->get();

        return view('blog.index', compact('posts', 'popular_posts'));
    }

    /**
     * Affiche un post spécifique
     */
    public function show(BlogPost $post)
    {
        // Vérifier si le post est publié
        if (!$post->is_published && 
            (!Auth::check() || 
             (Auth::id() !== $post->user_id && !Auth::user()->hasRole(['admin', 'moderator'])))) {
            abort(404);
        }

        // Incrémenter le compteur de vues
        $post->increment('views_count');

        // Charger les commentaires avec pagination
        $comments = $post->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Posts similaires
        $related_posts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Vérifier si l'utilisateur a liké le post
        $user_liked = Auth::check() ? $post->likes()->where('user_id', Auth::id())->exists() : false;

        return view('blog.show', compact('post', 'comments', 'related_posts', 'user_liked'));
    }

    /**
     * Formulaire de création de post
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Enregistre un nouveau post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|min:10',
            'is_published' => 'boolean',
        ]);

        $post = BlogPost::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'user_id' => Auth::id(),
            'is_published' => $request->boolean('is_published', true),
            'published_at' => $request->boolean('is_published', true) ? now() : null,
        ]);

        if ($post->is_published) {
            return redirect()->route('blog.show', $post->slug)
                ->with('success', 'Votre post a été publié avec succès !');
        } else {
            return redirect()->route('user.posts')
                ->with('success', 'Votre brouillon a été sauvegardé.');
        }
    }

    /**
     * Formulaire d'édition de post
     */
    public function edit(BlogPost $post)
    {
        $this->authorize('update', $post);
        return view('blog.edit', compact('post'));
    }

    /**
     * Met à jour un post
     */
    public function update(Request $request, BlogPost $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|min:10',
            'is_published' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'is_published' => $request->boolean('is_published'),
        ];

        // Mettre à jour le slug si le titre a changé
        if ($request->title !== $post->title) {
            $data['slug'] = Str::slug($request->title);
        }

        // Si on publie pour la première fois
        if (!$post->is_published && $request->boolean('is_published')) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()->route('blog.show', $post->slug)
            ->with('success', 'Post mis à jour avec succès !');
    }

    /**
     * Supprime un post
     */
    public function destroy(BlogPost $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();

        return redirect()->route('user.posts')
            ->with('success', 'Post supprimé avec succès.');
    }

    /**
     * Like/Unlike un post
     */
    public function like(BlogPost $post)
    {
        $user = Auth::user();
        
        $like = $post->likes()->where('user_id', $user->id)->first();
        
        if ($like) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà aimé ce post.'
            ]);
        }

        $post->likes()->create([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'likes_count' => $post->likes()->count(),
            'message' => 'Post aimé !'
        ]);
    }

    /**
     * Unlike un post
     */
    public function unlike(BlogPost $post)
    {
        $user = Auth::user();
        
        $like = $post->likes()->where('user_id', $user->id)->first();
        
        if (!$like) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas aimé ce post.'
            ]);
        }

        $like->delete();

        return response()->json([
            'success' => true,
            'likes_count' => $post->likes()->count(),
            'message' => 'Like retiré !'
        ]);
    }

    /**
     * Liste des posts pour l'admin
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('manage', BlogPost::class);

        $query = BlogPost::with('author');

        // Filtres
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('content', 'LIKE', '%' . $request->search . '%');
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Suppression admin d'un post
     */
    public function adminDestroy(BlogPost $post)
    {
        $this->authorize('manage', BlogPost::class);
        
        $post->delete();

        return redirect()->back()
            ->with('success', 'Post supprimé avec succès.');
    }
}