<?php

declare(strict_types=1);

namespace PhpAT\Statement\Event;

use PHPAT\EventDispatcher\EventInterface;

class StatementValidEvent implements EventInterface
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
