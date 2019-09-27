<?php

namespace PhpAT\Shared;

use Symfony\Component\EventDispatcher\Event;

abstract class EventWithMessage extends Event
{
    abstract public function getMessage(): string;
}
