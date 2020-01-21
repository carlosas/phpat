<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class AssertionsTest extends ArchitectureTest
{
    public function testAssertionsImplementAssertionInterface(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Rule/Assertion/*'))
            ->excludingClassesThat(Selector::havePath('Rule/Assertion/Assertion.php'))
            ->mustImplement()
            ->classesThat(Selector::haveClassName('PhpAT\Rule\Assertion\Assertion'))
            ->build();
    }
}
