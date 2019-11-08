<?php

declare(strict_types=1);

namespace PhpAT\Rule\Event;

use PhpAT\App\Event;

class RuleValidationStartEvent extends Event
{
    /**
     * @var string 
     */
    private $ruleName;

    public function __construct(string $ruleName)
    {
        $this->ruleName = $ruleName;
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->ruleName;
    }
}
