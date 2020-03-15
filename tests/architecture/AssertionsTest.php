<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

final class AssertionsTest extends ArchitectureTest
{
    public function testAssertionsImplementAssertionInterface(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Rule/Assertion/*'))
            ->excludingClassesThat(Selector::havePath('Rule/Assertion/AbstractAssertion.php'))
            ->mustExtend()
            ->classesThat(Selector::haveClassName('PhpAT\Rule\Assertion\AbstractAssertion'))
            ->build();
    }

    public function testAssertionsDoNotDependofVendors(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Rule/Assertion/*'))
            ->canOnlyDependOn()
            ->classesThat(Selector::haveClassName('PhpAT\*'))
            ->classesThat(Selector::haveClassName('Psr\*'))
            ->build();
    }
}
