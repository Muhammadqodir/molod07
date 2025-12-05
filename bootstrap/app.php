<?php

use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\ApiTokenAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureUserRole::class,
            'api.token.auth' => ApiTokenAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirect to main page when session/CSRF token expires (419 error)
        $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                return redirect('/')->with('error', 'Your session has expired. Please try again.');
            }
        });
    })->create();
