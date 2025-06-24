<?php

namespace Modules\Order\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'order');
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadViewsFrom(__DIR__.'/../Views', 'order');

        Blade::anonymousComponentPath(__DIR__.'/../Views/components', 'order');
        Blade::componentNamespace('Modules\Order\View\Components', 'order');

        $this->app->register(EventServiceProvider::class);
    }
}
