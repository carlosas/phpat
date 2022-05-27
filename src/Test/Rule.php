<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Rule\Assertion\Assertion;
use PHPat\Selector\SelectorInterface;

class Rule
{
    /** @var array<SelectorInterface> */
    public array $subjects = [];
    /** @var array<SelectorInterface> */
    public array $subjectExcludes = [];
    /** @var array<SelectorInterface> */
    public array $targets = [];
    /** @var array<SelectorInterface> */
    public array $targetExcludes = [];
    /** @var null|class-string<Assertion> */
    public ?string $assertion = null;
}
