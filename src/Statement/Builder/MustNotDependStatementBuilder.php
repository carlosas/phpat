<?php

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\Dependency\MustNotDepend\MustNotDepend;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\Rule;

class MustNotDependStatementBuilder implements StatementBuilder
{
    /** @var array<array{SelectorInterface, array<SelectorInterface>}> */
    private $statements = [];
    /** @var array<Rule> */
    private array $rules;

    /**
     * @param array<Rule> $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function build(): array
    {
        $params = $this->extractCurrentAssertion($this->rules);

        foreach ($params as $param) {
            $this->addStatement($param[0], $param[1]);
        }

        return $this->statements;
    }

    /*
     * @param array<Selector> $targets
     */
    public function addStatement(SelectorInterface $subject, array $targets): void
    {
        $this->statements[] = [$subject, $targets];
    }

    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule->assertion === MustNotDepend::class) {
                foreach ($rule->subjects as $selector) {
                    $result[] = [$selector, $rule->targets];
                }
            }
        }

        return $result;
    }
}
