<?php

use PhpAT\Selector\Selector;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class RuleTest extends ArchitectureTest
{
    public function testRuleDependsOnlyOnAssertionAndSelector(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(Rule::class))
            ->mustOnlyDependOn()
            ->classesThat(Selector::haveClassName(AbstractAssertion::class))
            ->andClassesThat(Selector::haveClassName(SelectorInterface::class))
            ->build();
    }
}
