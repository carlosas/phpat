<?php

declare(strict_types=1);

namespace PhpAT;

use PhpAT\Test\RuleBuilder;

class ArchitectureTest
{
    protected RuleBuilder $rule;

    public function __construct()
    {
        $this->rule = new RuleBuilder();
    }
}
