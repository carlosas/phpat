<?php declare(strict_types=1);

namespace PhpAT\Rule\Event;

use Symfony\Contracts\EventDispatcher\Event;

class StatementNotValidEvent extends Event
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
