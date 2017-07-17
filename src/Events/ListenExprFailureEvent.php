<?php

namespace Superbalist\LaravelEventPubSub\Events;

use Superbalist\EventPubSub\EventInterface;

class ListenExprFailureEvent
{
    /**
     * @var EventInterface
     */
    public $event;

    /**
     * @var
     */
    public $expr;

    /**
     * @param EventInterface $event
     * @param string $expr
     */
    public function __construct(EventInterface $event, $expr)
    {
        $this->event = $event;
        $this->expr = $expr;
    }
}
