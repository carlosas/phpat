<?php

declare(strict_types=1);

namespace PHPatOld\App\Event;

use PHPatOld\EventDispatcher\EventInterface;

class WarningEvent implements EventInterface
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
