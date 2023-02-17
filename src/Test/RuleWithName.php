<?php

declare(strict_types=1);

namespace PHPat\Test;

/**
 * @template T of Rule
 */
class RuleWithName
{
    private TestName $testName;
    private Rule $rule;

    /**
     * @param TestName $testName
     * @param T     $rule
     */
    public function __construct(TestName $testName, Rule $rule)
    {
        $this->testName = $testName;
        $this->rule     = $rule;
    }

    /**
     * @return TestName
     */
    public function getTestName(): TestName
    {
        return $this->testName;
    }

    /**
     * @return Rule
     */
    public function getRule(): Rule
    {
        return $this->rule;
    }
}
