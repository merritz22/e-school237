<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EvaluationSubjectController;
use App\Http\Controllers\EducationalResourceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FileController;
use App\Models\EvaluationSubject;
use App\Models\EducationalResource;
use Livewire\Component;
use App\Models\Article;
use App\Http\Controllers\LocaleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// oute pour la gestion des langues
Route::get('lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');
// Option 1 — Pages statiques Blade simples
Route::view('terms', 'pages.terms')->name('terms');
Route::view('privacy', 'pages.privacy')->name('privacy');

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
    
    // Gestion des matières
    Route::prefix('topics')->group(function () {
        Route::get('/', [SubjectController::class, 'adminIndex'])->name('admin.topics.index');
        Route::get('/create', [SubjectController::class, 'create'])->name('admin.topics.create');
        Route::post('/', [SubjectController::class, 'store'])->name('admin.topics.store');
        Route::get('/stats', [SubjectController::class, 'stats'])->name('admin.topics.stats');
        Route::get('/{topic}/edit', [SubjectController::class, 'edit'])->name('admin.topics.edit');
        Route::put('/{topic}', [SubjectController::class, 'update'])->name('admin.topics.update');
        Route::delete('/{topic}', [SubjectController::class, 'destroy'])->name('admin.topics.destroy');
        Route::patch('/{topic}/publish', [SubjectController::class, 'publish'])->name('admin.topics.publish');
    });
    
    // Gestion des classes
    Route::prefix('levels')->group(function () {
        Route::get('/', [LevelController::class, 'adminIndex'])->name('admin.levels.index');
        Route::get('/create', [LevelController::class, 'create'])->name('admin.levels.create');
        Route::post('/', [LevelController::class, 'store'])->name('admin.levels.store');
        Route::get('/stats', [LevelController::class, 'stats'])->name('admin.levels.stats');
        Route::get('/{level}/edit', [LevelController::class, 'edit'])->name('admin.levels.edit');
        Route::put('/{level}', [LevelController::class, 'update'])->name('admin.levels.update');
        Route::delete('/{level}', [LevelController::class, 'destroy'])->name('admin.levels.destroy');
        Route::patch('/{level}/publish', [LevelController::class, 'publish'])->name('admin.levels.publish');
    });

     // Gestion des abonnements
    Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('admin.subscriptions.index');
        Route::patch('/{subscription}/publish', [SubscriptionController::class, 'publish'])->name('admin.subscription.publish');
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
    Route::prefix('resources')->group(function () {
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
        
        // Publication
        Route::post('/{resource}/publish', [EducationalResourceController::class, 'publish'])
            ->name('admin.resources.publish');
        
            // dépublication
        Route::post('/{resource}/unpublish', [EducationalResourceController::class, 'unpublish'])
            ->name('admin.resources.unpublish');
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
    
    // Gestion des commentaires et posts de blog
    Route::prefix('blog')->group(function () {
        Route::get('/', [BlogController::class, 'adminIndex'])->name('admin.blog.index');
        Route::delete('/posts/{post}', [BlogController::class, 'adminDestroy'])->name('admin.blog.destroy');
        Route::get('/comments', [CommentController::class, 'adminIndex'])->name('admin.comments.index');
        Route::delete('/comments/{comment}', [CommentController::class, 'adminDestroy'])->name('admin.comments.destroy');
    });

    Route::fallback(function () {
        return redirect()->route('admin.dashboard');
    });
});


// Routes publiques
Route::get('/', function () {
    return redirect()->route('home');
});
Route::view('/home', 'pages.home')->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Authentification
Route::prefix('auth')->group(function () {
    Route::post('/logout', function(){
        Auth::logout();
        return redirect('/auth/login');
    })->name('logout');
    Route::view('/login', 'pages.auth.login')->name('login');
    Route::view('/register', 'pages.auth.register')->name('register');
    Route::view('/forgot-password', 'pages.auth.forgot-password')->name('password.request');
    Route::view('/reset-password/{token}', 'pages.auth.reset-password')->name('password.reset');
    Route::view('/verify-email', 'pages.auth.verify-email')->name('verification.notice');
});
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    
    session()->flash('status', 'Email envoyé!');
    
    return redirect('/home');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// Articles
Route::prefix('articles')->group(function () {
    Route::view('/', 'pages.articles.index')->name('articles.index');
    Route::get('/{article:slug}', function (Article $article) {
        return view('pages.articles.show', [
            'article' => $article
        ]);
    })->name('articles.show');
});

// Routes publiques (hors admin)
Route::prefix('resources')->group(function () {
    // Affichage public
    Route::view('/', 'pages.supports.index')
         ->name('resources.index');
    Route::get('/{resource}', function (EducationalResource $resource) {
        return view('pages.supports.show', [
            'resource' => $resource
        ]);
    })->name('resources.show')->middleware('resource_subscription');
    
    // Téléchargement
    Route::get('/{resource}/download', [EducationalResourceController::class, 'download'])
         ->name('resources.download');
});

// Sujets d'évaluation
Route::prefix('subjects')->group(function () {
    Route::view('/', 'pages.subjects.index')->name('subjects.index');
    Route::get('/{subject}', function (EvaluationSubject $subject) {
        return view('pages.subjects.show', [
            'subject' => $subject
        ]);
    })->name('subjects.show')->middleware('subject_subscription');
    Route::get('/download/{subject}', [EvaluationSubjectController::class, 'download'])->name('subjects.download')->middleware('auth');
});

// Gestion des souscriptions
Route::view('/subscription', 'pages.subscriptions.index')->name('subscriptions.index')->middleware('auth');
Route::view('/subscription/create', 'pages.subscriptions.store')->name('subscriptions.store')->middleware('auth');


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
    Route::view('/profile', 'pages.user.profile')->name('user.profile');
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

Route::get('/evaluation_subject/pdf/{id}', function ($id) {
    $subject = EvaluationSubject::findOrFail($id);
    return response()->file(
        storage_path('app/private/' . $subject->file_path)
    );
})->middleware('subject_subscription');

Route::get('/support/pdf/{id}', function ($id) {
    $support = EducationalResource::findOrFail($id);
    return response()->file(
        storage_path('app/private/' . $support->file_path)
    );
})->middleware('resource_subscription');

Route::get('/show/subject/pdf/{id}', function ($id) {
    $subject = EvaluationSubject::findOrFail($id);
    return response()->file(
        storage_path('app/private/' . $subject->file_path)
    );
});

Route::get('/show/support/pdf/{id}', function ($id) {
    $support = EducationalResource::findOrFail($id);
    return response()->file(
        storage_path('app/private/' . $support->file_path)
    );
});


// Route pour le paiement par api - Orange Money / MTN

// Initiation du paiement
Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment'])->name('initiate');
// Orange Money appellera ton serveur après paiement.
Route::post('/payments/callback', [PaymentController::class, 'callback'])
     ->name('mtn.callback');

Route::fallback(function () {
    return redirect()->route('home');
});