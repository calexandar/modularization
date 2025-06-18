<?php

namespace Modules\Payment\Infrastructure\Providers;

use Modules\Payment\PayBuddy;
use Modules\Payment\PaymentGateway;
use Modules\Payment\PayBuddyGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->bind(PaymentGateway::class, fn () => new PayBuddyGateway(new PayBuddy()));
    }
}
