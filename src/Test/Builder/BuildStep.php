<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Test\Rule;

class BuildStep
{
    protected Rule $rule;

    public function __construct(Rule $rule)
    {
        $this->rule = $rule;
    }

    public function build(): Rule
    {
        return $this->rule;
    }
}
