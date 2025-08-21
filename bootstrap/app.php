<?php

use App\Http\Middleware\AssignGuard;
use App\Http\Middleware\Localization;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
           'guest' => RedirectIfAuthenticated::class,
           'assign.guard' => AssignGuard::class,
           'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);

        $middleware->web(
            append: [
                Localization::class
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
