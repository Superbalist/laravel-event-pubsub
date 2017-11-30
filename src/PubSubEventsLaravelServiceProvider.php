<?php

namespace Superbalist\LaravelEventPubSub;

use Superbalist\LaravelPubSub\PubSubLaravelServiceProvider;

class PubSubEventsLaravelServiceProvider extends PubSubEventsBaseServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pubsub_events.php', 'pubsub_events');
        $this->app->register(PubSubLaravelServiceProvider::class);

        Parent::register();
    }
}
