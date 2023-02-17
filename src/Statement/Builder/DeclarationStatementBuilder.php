<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;
use PHPat\Test\RuleWithName;
use PHPat\Test\TestName;
use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

class DeclarationStatementBuilder implements StatementBuilder
{
    /** @var array<array{TestName, SelectorInterface, array<SelectorInterface>}> */
    protected $statements = [];
    /** @var array<RuleWithName<RelationRule>> */
    protected array $rules;
    /** @var class-string<PHPStanRule<Node>> */
    private string $assertion;

    /**
     * @param class-string<PHPStanRule<Node>> $assertion
     * @param array<RuleWithName<RelationRule>> $rules
     */
    final public function __construct(string $assertion, array $rules)
    {
        $this->assertion = $assertion;
        $this->rules     = $rules;
    }

    /**
     * @return array<array{TestName, SelectorInterface, array<SelectorInterface>}>
     */
    public function build(): array
    {
        $params = $this->extractCurrentAssertion($this->rules);

        foreach ($params as $param) {
            $this->addStatement($param[0], $param[1], $param[2]);
        }

        return $this->statements;
    }

    /**
     * @param array<SelectorInterface> $subjectExcludes
     */
    private function addStatement(
        TestName $testName,
        SelectorInterface $subject,
        array $subjectExcludes
    ): void {
        $this->statements[] = [$testName, $subject, $subjectExcludes];
    }

    /**
     * @param array<RuleWithName<RelationRule>> $rules
     * @return array<array{TestName, SelectorInterface, array<SelectorInterface>}>
     */
    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule->getRule()->getAssertion() === $this->assertion) {
                foreach ($rule->getRule()->getSubjects() as $selector) {
                    $result[] = [$rule->getTestName(), $selector, $rule->getRule()->getSubjectExcludes()];
                }
            }
        }

        return $result;
    }
}
