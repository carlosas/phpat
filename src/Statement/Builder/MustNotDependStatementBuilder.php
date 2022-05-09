<?php

namespace PhpAT\Statement\Builder;

use PhpAT\Rule\Assertion\Dependency\MustNotDepend\MustNotDepend;

class MustNotDependStatementBuilder implements StatementBuilder
{
    /** @var array<class-string, array<class-string>> */
    private $statements = [];
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function build(): array
    {
        $params = $this->extractCurrentAssertion($this->rules);

        foreach ($params as $subject => $targets) {
            $this->addStatement($subject, $targets);
        }

        return $this->statements;
    }

    /*
     * @param array<class-string> $targets
     */
    public function addStatement(string $subject, array $targets): void
    {
        $this->statements[$subject] = $targets;
    }

    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule['assertion'] === MustNotDepend::class) {
                foreach ($rule['subjects'] as $subject) {
                    $result[$subject] = $rule['targets'];
                }
            }
        }

        return $result;
    }
}
