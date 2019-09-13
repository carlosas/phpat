<?php

use PhpAT\Rule\Type\Inheritance;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class RuleTypeTest extends ArchitectureTest
{
    public function testRuleTypesImplementRuleTypeInterface(): Rule
    {
        return $this->newRule
            ->filesLike('Rule/Type/*')
            ->excluding('Rule/Type/RuleType.php')
            ->shouldNotHave(Inheritance::class)
            ->withParams([
                'file' => 'Rule/Type/RuleType.php'
            ])
            ->build();
    }
}
