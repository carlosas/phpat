<?php

namespace PhpAT\Statement;

class StatementNotValidException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
