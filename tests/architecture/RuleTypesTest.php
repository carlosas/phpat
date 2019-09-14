<?php

use PhpAT\Rule\Type\Composition;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class RuleTypesTest extends ArchitectureTest
{
    public function testRuleTypesImplementRuleTypeInterface(): Rule
    {
        return $this->newRule
            ->filesLike('Rule/Type/*')
            ->excluding('Rule/Type/RuleType.php')
            ->shouldHave(Composition::class)
            ->withParams([
                'files' => ['Rule/Type/RuleType.php']
            ])
            ->build();
    }
}
