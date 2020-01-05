<?php

declare(strict_types=1);

namespace PhpAT\Rule\Event;

use PHPAT\EventDispatcher\EventInterface;

class RuleValidationStartEvent implements EventInterface
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
