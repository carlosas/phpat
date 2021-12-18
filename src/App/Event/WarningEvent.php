<?php

declare(strict_types=1);

namespace PhpAT\App\Event;

use PHPAT\EventDispatcher\EventInterface;

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
