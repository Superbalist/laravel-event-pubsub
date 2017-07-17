<?php

namespace Superbalist\LaravelEventPubSub\Events;

class TranslationFailureEvent
{
    /**
     * @var string
     */
    public $message;

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
