<?php

namespace Modules\Payment\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Payment\PayBuddy;
use Modules\Payment\PayBuddyGateway;
use Modules\Payment\PaymentGateway;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->bind(PaymentGateway::class, fn () => new PayBuddyGateway(new PayBuddy));
    }
}
