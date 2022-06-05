<?php

namespace Tests\PHPat\Architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;

class AssertionsTest extends ArchitectureTest
{
    public function testAssertionsImplementAssertionInterface(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Rule/Assertion/*'))
            ->excludingClassesThat(SelectorInterface::haveClassName('PHPat\Rule\Assertion\*\MustNot*'))
            ->excludingClassesThat(SelectorInterface::havePath('Rule/Assertion/MatchResult.php'))
            ->excludingClassesThat(SelectorInterface::havePath('Rule/Assertion/AbstractAssertion.php'))
            ->mustExtend()
            ->classesThat(SelectorInterface::haveClassName('PHPat\Rule\Assertion\AbstractAssertion'))
            ->build();
    }

    public function testAssertionsOnlyDependPhpatAndPsr(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Rule/Assertion/*'))
            ->canOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName('PHPat\*'))
            ->andClassesThat(SelectorInterface::haveClassName('Psr\*'))
            ->build();
    }
}
