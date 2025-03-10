<?php

use App\Providers\AppServiceProvider;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middlewares globais (aplicados a todas as rotas)
        $middleware->append(\App\Http\Middleware\CorsMiddleware::class);

        // Middlewares específicos para o grupo 'web'
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Middlewares específicos para o grupo 'api'
        $middleware->api(append: [
            \App\Http\Middleware\CorsMiddleware::class,
        ]);
    })
    ->withProviders([
        AppServiceProvider::class,
        AuthServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
