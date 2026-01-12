<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        
        // Redirect authenticated users away from guest routes
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();
            if ($user && $user->role === 'admin') {
                return '/admin/dashboard';
            }
            return '/wali/dashboard';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
