<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Test\Builder\Rule as RuleBuilder;
use PHPat\Test\TestName;

class RuleBuilderWithName
{
    private TestName $testName;
    private RuleBuilder $ruleBuilder;
    public function __construct(TestName $testName, RuleBuilder $ruleBuilder)
    {
        $this->testName    = $testName;
        $this->ruleBuilder = $ruleBuilder;
    }

    /**
     * @return TestName
     */
    public function getTestName(): TestName
    {
        return $this->testName;
    }

    /**
     * @return RuleBuilder
     */
    public function getRuleBuilder(): RuleBuilder
    {
        return $this->ruleBuilder;
    }
}
