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
        // Render terminates SSL at its edge and proxies to the container
        // over plain HTTP, setting X-Forwarded-Proto: https. Without
        // trusting that header, Laravel thinks every request is HTTP and
        // generates insecure http:// links for route()/url()/forms, which
        // is why the browser flagged the category filter form as insecure.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
