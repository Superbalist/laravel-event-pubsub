<?php

namespace Superbalist\LaravelEventPubSub;

use Illuminate\Support\Facades\Facade;

class PubSubEventsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pubsub.events';
    }
}
