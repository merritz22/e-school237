<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Liste des articles publiques
     */
    public function index(Request $request)
    {
        $query = Article::with('category', 'author')->where('status','published');

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $articles = $query->paginate(12);
        $categories = Category::whereHas('articles')->withCount('articles')->orderBy('name')->get();

        return view('articles.index', compact('articles', 'categories'));
    }

    /**
     * Affiche un article spécifique
     */
    public function show(Article $article)
    {
        // Vérifier si l'article est publié (sauf pour l'auteur et les admins)
        if (($article->status != 'published') && !(Auth::user()->hasRole(['admin']))) {
            abort(404);
        }

        // Incrémenter le compteur de vues
        $article->increment('views_count');

        // Articles similaires
        $related_articles = Article::where('status','published')
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Charger les commentaires
        // $article->load(['comments' => function ($query) {
        //     $query->with('user')->orderBy('created_at', 'desc');
        // }]);

        return view('articles.show', compact('article', 'related_articles'));
    }

    /**
     * Articles par catégorie
     */
    public function byCategory(Category $category)
    {
        $articles = Article::with('author')
            ->published()
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('articles.category', compact('articles', 'category'));
    }

    /**
     * Liste des articles pour l'admin
     */
    public function adminIndex(Request $request)
    {
        // $this->authorize('manage', Article::class);
        Auth::user()->hasRole([ 'admin', 'author']);

        $query = Article::with('category', 'author');

        // Filtres
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::where('type','subject')->orderBy('name')->get();
        $authors = User::where('role', 'admin')->get();

        return view('admin.articles.index', compact('articles', 'categories', 'authors'));
    }

    /**
     * Formulaire de création d'article
     */
    public function create()
    {
        // $this->authorize('create', Article::class);
        Auth::user()->hasRole([ 'admin', 'author']);
        
        $categories = Category::where('type','subject')->orderBy('name')->get();
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Enregistre un nouvel article
     */
    public function store(Request $request)
    {
        // $this->authorize('create', Article::class);
        Auth::user()->hasRole([ 'admin', 'author']);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|max:500',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['author_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);

        // Gestion de l'image à la une
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        // Gestion de la date de publication
        if ($request->is_published && !$request->published_at) {
            $data['published_at'] = now();
        }

        $article = Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article créé avec succès.');
    }

    /**
     * Formulaire d'édition d'article
     */
    public function edit(Article $article)
    {
        // $this->authorize('update', $article);
        Auth::user()->hasRole([ 'admin', 'author']);
        
        $categories = Category::orderBy('name')->get();
        $tags = TAG::all();
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    /**
     * Met à jour un article
     */
    public function update(Request $request, Article $article)
    {
        // $this->authorize('update', $article);
        Auth::user()->hasRole([ 'admin', 'author']);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|max:500',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->all();
        
        // Mettre à jour le slug si le titre a changé
        if ($request->title !== $article->title) {
            $data['slug'] = Str::slug($request->title);
        }

        // Gestion de l'image à la une
        if ($request->hasFile('featured_image')) {
            // Supprimer l'ancienne image
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        // Gestion de la date de publication
        if ($request->is_published && !$article->is_published && !$request->published_at) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Supprime un article
     */
    public function destroy(Article $article)
    {
        // $this->authorize('delete', $article);
        Auth::user()->hasRole([ 'admin', 'author']);

        // Supprimer l'image à la une
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès.');
    }

    /**
     * Publier/dépublier un article
     */
    public function publish(Article $article)
    {
        // $this->authorize('update', $article);
        Auth::user()->hasRole([ 'admin', 'author']);
        // dd(now());

        $article->update([
            'status' => $article->status == 'draft' || $article->status == 'archived' ? 'published' : 'draft',
            'published_at' => $article->status == 'draft' || $article->status == 'archived' ? now() : null
        ]);

        $status = $article->status == 'published' ? 'publié' : 'dépublié';
        return redirect()->back()->with('success', "Article {$status} avec succès.");
    }
}