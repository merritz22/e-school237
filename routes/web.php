<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EvaluationSubjectController;
use App\Http\Controllers\EducationalResourceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Administration
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/stats', [DashboardController::class, 'stats'])->name('admin.stats');
    
    // Gestion des utilisateurs
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::patch('/{user}/suspend', [AdminUserController::class, 'suspend'])->name('admin.users.suspend');
        Route::patch('/{user}/activate', [AdminUserController::class, 'activate'])->name('admin.users.activate');
        Route::patch('/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.role');
    });
    
    // Gestion des articles
    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'adminIndex'])->name('admin.articles.index');
        Route::get('/create', [ArticleController::class, 'create'])->name('admin.articles.create');
        Route::post('/', [ArticleController::class, 'store'])->name('admin.articles.store');
        Route::get('/stats', [ArticleController::class, 'stats'])->name('admin.articles.stats');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('admin.articles.edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('admin.articles.update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');
        Route::patch('/{article}/publish', [ArticleController::class, 'publish'])->name('admin.articles.publish');
    });
    
    // Gestion des sujets
    Route::prefix('subjects')->group(function () {
        Route::get('/', [EvaluationSubjectController::class, 'adminIndex'])->name('admin.subjects.index');
        Route::get('/create', [EvaluationSubjectController::class, 'create'])->name('admin.subjects.create');
        Route::post('/', [EvaluationSubjectController::class, 'store'])->name('admin.subjects.store');
        Route::get('/{subject}/edit', [EvaluationSubjectController::class, 'edit'])->name('admin.subjects.edit');
        Route::put('/{subject}', [EvaluationSubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/{subject}', [EvaluationSubjectController::class, 'destroy'])->name('admin.subjects.destroy');
        Route::get('/{subject}/download', [EvaluationSubjectController::class, 'download'])->name('admin.subjects.download');
    });

    // Gestion des ressources éducatives
    Route::prefix('admin/resources')->group(function () {
        // Liste des ressources (avec gestion d'approbation)
        Route::get('/', [EducationalResourceController::class, 'adminIndex'])
            ->name('admin.resources.index');
        
        // Création
        Route::get('/create', [EducationalResourceController::class, 'create'])
            ->name('admin.resources.create');
        Route::post('/', [EducationalResourceController::class, 'store'])
            ->name('admin.resources.store');
        
        // Édition
        Route::get('/{resource}/edit', [EducationalResourceController::class, 'edit'])
            ->name('admin.resources.edit');
        Route::put('/{resource}', [EducationalResourceController::class, 'update'])
            ->name('admin.resources.update');
        
        // Suppression
        Route::delete('/{resource}', [EducationalResourceController::class, 'destroy'])
            ->name('admin.resources.destroy');
        
        // Approbation
        Route::post('/{resource}/approve', [EducationalResourceController::class, 'approve'])
            ->name('admin.resources.approve');
        Route::post('/{resource}/reject', [EducationalResourceController::class, 'reject'])
            ->name('admin.resources.reject');
    });
    
    // Gestion des supports
    Route::prefix('supports')->group(function () {
        Route::get('/', [SupportController::class, 'adminIndex'])->name('admin.supports.index');
        Route::get('/create', [SupportController::class, 'create'])->name('admin.supports.create');
        Route::post('/', [SupportController::class, 'store'])->name('admin.supports.store');
        Route::get('/{support}/edit', [SupportController::class, 'edit'])->name('admin.supports.edit');
        Route::put('/{support}', [SupportController::class, 'update'])->name('admin.supports.update');
        Route::delete('/{support}', [SupportController::class, 'destroy'])->name('admin.supports.destroy');
    });
    
    // Gestion des catégories
    Route::prefix('categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/{category}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });
    
    // Gestion des commentaires et posts de blog
    Route::prefix('blog')->group(function () {
        Route::get('/', [BlogController::class, 'adminIndex'])->name('admin.blog.index');
        Route::delete('/posts/{post}', [BlogController::class, 'adminDestroy'])->name('admin.blog.destroy');
        Route::get('/comments', [CommentController::class, 'adminIndex'])->name('admin.comments.index');
        Route::delete('/comments/{comment}', [CommentController::class, 'adminDestroy'])->name('admin.comments.destroy');
    });
});

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');


// Routes publiques (hors admin)
Route::prefix('resources')->group(function () {
    // Affichage public
    Route::get('/', [EducationalResourceController::class, 'index'])
         ->name('resources.index');
    Route::get('/{resource}', [EducationalResourceController::class, 'show'])
         ->name('resources.show');
    
    // Téléchargement
    Route::get('/{resource}/download', [EducationalResourceController::class, 'download'])
         ->name('resources.download');
});

// Authentification
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Articles
Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/category/{category:slug}', [ArticleController::class, 'byCategory'])->name('articles.category');
});

// Sujets d'évaluation
Route::prefix('subjects')->group(function () {
    Route::get('/', [EvaluationSubjectController::class, 'index'])->name('subjects.index');
    Route::get('/{subject}', [EvaluationSubjectController::class, 'show'])->name('subjects.show');
    Route::get('/download/{subject}', [EvaluationSubjectController::class, 'download'])->name('subjects.download')->middleware('auth');
    Route::get('/level/{level}', [EvaluationSubjectController::class, 'byLevel'])->name('subjects.level');
    Route::get('/subject/{subject_name}', [EvaluationSubjectController::class, 'bySubject'])->name('subjects.subject');
});

// Supports pédagogiques
Route::prefix('supports')->group(function () {
    Route::get('/', [SupportController::class, 'index'])->name('supports.index');
    Route::get('/{support}', [SupportController::class, 'show'])->name('supports.show');
    Route::get('/download/{support}', [SupportController::class, 'download'])->name('supports.download')->middleware('auth');
    Route::get('/type/{type}', [SupportController::class, 'byType'])->name('supports.type');
    Route::get('/preview/{support}', [SupportController::class, 'preview'])->name('supports.preview');
});

// Blog/Forum
Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
    
    Route::middleware('auth')->group(function () {
        Route::get('/create', [BlogController::class, 'create'])->name('blog.create');
        Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
        Route::get('/{post}/edit', [BlogController::class, 'edit'])->name('blog.edit');
        Route::put('/{post}', [BlogController::class, 'update'])->name('blog.update');
        Route::delete('/{post}', [BlogController::class, 'destroy'])->name('blog.destroy');
        Route::post('/{post}/like', [BlogController::class, 'like'])->name('blog.like');
        Route::delete('/{post}/unlike', [BlogController::class, 'unlike'])->name('blog.unlike');
    });
});

// Commentaires
Route::middleware('auth')->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
});

// Espace utilisateur
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::get('/downloads', [UserController::class, 'downloads'])->name('user.downloads');
    Route::get('/posts', [UserController::class, 'posts'])->name('user.posts');
    Route::get('/history', [UserController::class, 'history'])->name('user.history');
});

// Upload de fichiers
Route::middleware('auth')->group(function () {
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
});
