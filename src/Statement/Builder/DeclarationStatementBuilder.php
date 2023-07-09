<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Selector\SelectorInterface;
use PHPat\Test\RelationRule;
use PHPat\Test\Rule;
use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

class DeclarationStatementBuilder implements StatementBuilder
{
    /** @var array<array{string, SelectorInterface, array<SelectorInterface>}> */
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
     * @return array<array{string, SelectorInterface, array<SelectorInterface>}>
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
     * @param string[] $tips
     */
    private function addStatement(
        string $ruleName,
        SelectorInterface $subject,
        array $subjectExcludes,
        array $tips
    ): void {
        $this->statements[] = [$ruleName, $subject, $subjectExcludes, $tips];
    }

    /**
     * @param array<Rule> $rules
     * @return array<array{string, SelectorInterface, array<SelectorInterface>}>
     */
    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule->getAssertion() === $this->assertion) {
                foreach ($rule->getSubjects() as $selector) {
                    $result[] = [$rule->getRuleName(), $selector, $rule->getSubjectExcludes(), $rule->getTips()];
                }
            }
        }

        return $result;
    }
}
