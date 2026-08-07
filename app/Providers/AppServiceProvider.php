<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendNewUserNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\EducationalResource;
use App\Models\EvaluationSubject;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            Registered::class,
            SendNewUserNotification::class,
        );

        // Permet à DownloadLog::resource() (morphTo) de résoudre 'resource'/'evaluation'
        // stockés dans download_logs.resource_type vers les bons modèles.
        Relation::morphMap([
            'resource' => EducationalResource::class,
            'evaluation' => EvaluationSubject::class,
        ]);
    }
}
