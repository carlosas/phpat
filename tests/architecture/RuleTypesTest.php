<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class RuleTypesTest extends ArchitectureTest
{
    public function testRuleTypesImplementRuleTypeInterface(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Rule/Type/*'))
            ->excludingClassesThat(Selector::havePath('Rule/Type/RuleType.php'))
            ->mustImplement()
            ->classesThat(Selector::havePath('Rule/Type/RuleType.php'))
            ->build();
    }
}
