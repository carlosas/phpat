<?php

declare(strict_types=1);

namespace PhpAT\App\Event;

use PHPAT\EventDispatcher\EventInterface;

class WarningEvent implements EventInterface
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
