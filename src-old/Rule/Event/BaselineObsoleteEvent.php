<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Event;

use PHPatOld\EventDispatcher\EventInterface;

class BaselineObsoleteEvent implements EventInterface
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
