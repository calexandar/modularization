<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(__DIR__.'/../Http/routes.php');
        });
    }
}
