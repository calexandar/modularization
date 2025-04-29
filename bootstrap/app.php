<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
     
            Route::middleware('web')
                ->group(base_path('modules/order/routes.php'));

            Route::middleware('web')
                ->group(base_path('modules/product/routes.php'));

            Route::middleware('web')
                ->group(base_path('modules/shipment/routes.php'));
     
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
