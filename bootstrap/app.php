<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'subject_subscription' => \App\Http\Middleware\CheckSubjectSubscription::class,//Vérifie si l'utilisateur à un abonnement en cours ou pas 
            'resource_subscription' => \App\Http\Middleware\CheckResourceSubscription::class,//Vérifie si l'utilisateur à un abonnement en cours ou pas
        ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
