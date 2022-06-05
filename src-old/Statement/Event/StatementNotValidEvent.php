<?php

declare(strict_types=1);

namespace PHPatOld\Statement\Event;

use PHPatOld\EventDispatcher\EventInterface;

class StatementNotValidEvent implements EventInterface
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
