<?php

namespace Superbalist\LaravelEventPubSub;

use Superbalist\LaravelPubSub\PubSubLumenServiceProvider;

class PubSubEventsLumenServiceProvider extends PubSubEventsBaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->app->configure('pubsub_events');
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pubsub_events.php', 'pubsub_events');
        $this->app->register(PubSubLumenServiceProvider::class);

        Parent::register();
    }
}
