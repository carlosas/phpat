<?php

namespace PHPatOld\Test;

use PHPatOld\Rule\RuleCollection;

interface TestInterface
{
    public function __invoke(): RuleCollection;
}
