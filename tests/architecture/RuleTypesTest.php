<?php

use PhpAT\Rule\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class RuleTypesTest extends ArchitectureTest
{
    public function testRuleTypesImplementRuleTypeInterface(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Rule/Type/*'))
            ->excludingClassesThat(Selector::havePathname('Rule/Type/RuleType.php'))
            ->shouldImplement()
            ->classesThat(Selector::havePathname('Rule/Type/RuleType.php'))
            ->build();
    }
}
