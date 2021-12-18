<?php

declare(strict_types=1);

namespace PhpAT\Rule\Event;

use PHPAT\EventDispatcher\EventInterface;

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
