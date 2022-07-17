<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Rule\Assertion\Relation\Assertion;
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
    /** @var null|class-string<\PHPat\Rule\Assertion\Relation\Assertion> */
    public ?string $assertion = null;
}
