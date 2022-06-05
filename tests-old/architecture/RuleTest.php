<?php

namespace Tests\PHPat\Architecture;

use PHPat\Rule\Assertion\AbstractAssertion;
use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;

class RuleTest extends ArchitectureTest
{
    public function testRuleDependsOnlyOnAssertionAndSelector(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(Rule::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(AbstractAssertion::class))
            ->andClassesThat(SelectorInterface::haveClassName(SelectorInterface::class))
            ->build();
    }

    public function testRuleBuilderDependsOnlyOnPhpatAndPsrContainer(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(\PHPat\Rule\TestParser::class))
            ->canOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName('PHPat\\*'))
            ->andClassesThat(SelectorInterface::haveClassName(\Psr\Container\ContainerInterface::class))
            ->build();
    }
}
