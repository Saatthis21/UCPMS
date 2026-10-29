<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserIsActive;
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

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        |
        | Existing UCPMS role middleware.
        |
        */

        $middleware->alias([
            'role' => CheckRole::class,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Active User Protection
        |--------------------------------------------------------------------------
        |
        | Run EnsureUserIsActive for web requests.
        |
        | If an authenticated user's account is deactivated by the
        | System Administrator, the middleware will terminate that
        | user's existing session on their next request.
        |
        */

        $middleware->appendToGroup('web', [
            EnsureUserIsActive::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();