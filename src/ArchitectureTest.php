<?php

declare(strict_types=1);

namespace PhpAT;

use PhpAT\Test\RuleBuilder;

class ArchitectureTest
{
    protected function rule(): RuleBuilder
    {
        return new RuleBuilder();
    }
}
