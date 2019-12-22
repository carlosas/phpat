<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class ExtractorsTest extends ArchitectureTest
{
    public function testExtractorsDependOnRuleBuilder(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::implementInterface(\PhpAT\Test\TestExtractor::class))
            ->mustDependOn()
            ->classesThat(Selector::haveClassName(\PhpAT\Rule\RuleBuilder::class))
            ->build();
    }
}
