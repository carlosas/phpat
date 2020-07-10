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
            ->excludingClassesThat(Selector::haveClassName('PhpAT\Rule\Assertion\*\MustNot*'))
            ->excludingClassesThat(Selector::havePath('Rule/Assertion/MatchResult.php'))
            ->excludingClassesThat(Selector::havePath('Rule/Assertion/AbstractAssertion.php'))
            ->mustExtend()
            ->classesThat(Selector::haveClassName('PhpAT\Rule\Assertion\AbstractAssertion'))
            ->build();
    }

    public function testAssertionsOnlyDependPhpATAndPsr(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Rule/Assertion/*'))
            ->canOnlyDependOn()
            ->classesThat(Selector::haveClassName('PhpAT\*'))
            ->build();
    }
}
