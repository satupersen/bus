<?php

namespace Satupersen\Bus;

use Satupersen\Contracts\Bus\Dispatcher as DispatcherContract;
use Satupersen\Contracts\Bus\QueueingDispatcher as QueueingDispatcherContract;
use Satupersen\Contracts\Queue\Factory as QueueFactoryContract;
use Satupersen\Contracts\Support\DeferrableProvider;
use Satupersen\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Dispatcher::class, function ($app) {
            return new Dispatcher($app, function ($connection = null) use ($app) {
                return $app[QueueFactoryContract::class]->connection($connection);
            });
        });

        $this->app->alias(
            Dispatcher::class, DispatcherContract::class
        );

        $this->app->alias(
            Dispatcher::class, QueueingDispatcherContract::class
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Dispatcher::class,
            DispatcherContract::class,
            QueueingDispatcherContract::class,
        ];
    }
}
