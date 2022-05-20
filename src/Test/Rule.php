<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Selector\SelectorInterface;

class Rule
{
    /** @var array<SelectorInterface> */
    public array $subjects = [];
    /** @var array<SelectorInterface> */
    public array $targets = [];
    /** @var null|class-string<Rule> */
    public ?string $assertion = null;

    public function __construct(array $subjects, array $targets, ?string $assertion)
    {
        $this->subjects = $subjects;
        $this->targets = $targets;
        $this->assertion = $assertion;
    }
}
