<?php declare(strict_types=1);

namespace PhpAT\Statement\Event;

use PhpAT\Shared\EventWithMessage;

class StatementNotValidEvent extends EventWithMessage
{
    /** @var string */
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
