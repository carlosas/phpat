<?php

declare(strict_types=1);

namespace PhpAT\Statement\Event;

use PhpAT\App\Event;

class StatementNotValidEvent extends Event
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
