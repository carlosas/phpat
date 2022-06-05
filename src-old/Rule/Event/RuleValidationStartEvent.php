<?php

declare(strict_types=1);

namespace PHPatOld\Rule\Event;

use PHPatOld\EventDispatcher\EventInterface;

class RuleValidationStartEvent implements EventInterface
{
    private string $ruleName;

    public function __construct(string $ruleName)
    {
        $this->ruleName = $ruleName;
    }

    public function getRuleName(): string
    {
        return $this->ruleName;
    }
}
