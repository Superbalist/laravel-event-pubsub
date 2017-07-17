<?php

namespace Superbalist\LaravelEventPubSub\Events;

use Superbalist\EventPubSub\ValidationResult;

class ValidationFailureEvent
{
    /**
     * @var ValidationResult
     */
    public $result;

    /**
     * @param ValidationResult $result
     */
    public function __construct(ValidationResult $result)
    {
        $this->result = $result;
    }
}
