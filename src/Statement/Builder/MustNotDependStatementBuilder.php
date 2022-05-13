<?php

namespace PhpAT\Statement\Builder;

use PhpAT\Rule\Assertion\Dependency\MustNotDepend\MustNotDepend;
use PhpAT\Selector\Selector;

class MustNotDependStatementBuilder implements StatementBuilder
{
    /** @var array<array{Selector, array<class-string>}> */
    private $statements = [];
    private array $rules;

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
     * @param array<class-string> $targets
     */
    public function addStatement(Selector $subject, array $targets): void
    {
        $this->statements[] = [$subject, $targets];
    }

    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule['assertion'] === MustNotDepend::class) {
                foreach ($rule['subjects'] as $selector) {
                    $result[] = [$selector, $rule['targets']];
                }
            }
        }

        return $result;
    }
}
