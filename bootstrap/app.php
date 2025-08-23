<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => \App\Http\Middleware\OnlyGuestAllowedMiddleware::class
        ]);
        $middleware->appendToGroup('admin', [
            Illuminate\Auth\Middleware\Authenticate::class,
            \App\Http\Middleware\CheckAdmin::class,
        ]);
        $middleware->appendToGroup('warehouse-manager', [
            Illuminate\Auth\Middleware\Authenticate::class,
            \App\Http\Middleware\CheckWarehouseManager::class,
        ]);
        $middleware->appendToGroup('deputy-warehouse-manager', [
            Illuminate\Auth\Middleware\Authenticate::class,
            \App\Http\Middleware\CheckDeputyWarehouseManager::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
