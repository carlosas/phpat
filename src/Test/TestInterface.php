<?php

namespace PhpAT\Test;

use PhpAT\Rule\RuleCollection;

interface TestInterface
{
    public function __invoke(): RuleCollection;
}