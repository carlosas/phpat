<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;
use PHPat\Test\Rule;
use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

class RelationStatementBuilder implements StatementBuilder
{
    /** @var array<array{SelectorInterface, array<SelectorInterface>, array<SelectorInterface>, array<SelectorInterface>}> */
    protected $statements = [];
    /** @var array<RelationRule> */
    protected array $rules;
    /** @var class-string<PHPStanRule<Node>> */
    private string $assertion;

    /**
     * @param class-string<PHPStanRule<Node>> $assertion
     * @param array<RelationRule> $rules
     */
    final public function __construct(string $assertion, array $rules)
    {
        $this->assertion = $assertion;
        $this->rules     = $rules;
    }

    /**
     * @return array<array{SelectorInterface, array<SelectorInterface>, array<SelectorInterface>, array<SelectorInterface>}>
     */
    public function build(): array
    {
        $params = $this->extractCurrentAssertion($this->rules);

        foreach ($params as $param) {
            $this->addStatement($param[0], $param[1], $param[2], $param[3]);
        }

        return $this->statements;
    }

    /**
     * @param array<SelectorInterface> $subjectExcludes
     * @param array<SelectorInterface> $targets
     * @param array<SelectorInterface> $targetExcludes
     */
    private function addStatement(
        SelectorInterface $subject,
        array $subjectExcludes,
        array $targets,
        array $targetExcludes
    ): void {
        $this->statements[] = [$subject, $subjectExcludes, $targets, $targetExcludes];
    }

    /**
     * @param array<Rule> $rules
     * @return array<array{SelectorInterface, array<SelectorInterface>, array<SelectorInterface>, array<SelectorInterface>}>
     */
    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule->getAssertion() === $this->assertion) {
                foreach ($rule->getSubjects() as $selector) {
                    $result[] = [$selector, $rule->getSubjectExcludes(), $rule->getTargets(), $rule->getTargetExcludes()];
                }
            }
        }

        return $result;
    }
}
