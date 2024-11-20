<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AddSecurityHeaders;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\FetchArticles;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [Illuminate\Routing\Middleware\ThrottleRequests::class,]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
