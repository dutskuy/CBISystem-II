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
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin'       => \App\Http\Middleware\AdminMiddleware::class,
        'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
        'admin_gudang' => \App\Http\Middleware\AdminGudangMiddleware::class,
        'owner'       => \App\Http\Middleware\OwnerMiddleware::class,
        'customer'    => \App\Http\Middleware\CustomerMiddleware::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
            $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.403', [], 403);
            }
        });        
    })
->create();
