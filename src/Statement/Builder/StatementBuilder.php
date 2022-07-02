<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Selector\SelectorInterface;
use PHPat\Test\Rule;
use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

abstract class StatementBuilder
{
    /** @var array<array{SelectorInterface, array<SelectorInterface>}> */
    protected $statements = [];
    /** @var array<Rule> */
    protected array $rules;

    /**
     * @param array<Rule> $rules
     */
    final public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return array<array{SelectorInterface, array<SelectorInterface>}>
     */
    public function build(): array
    {
        $params = $this->extractCurrentAssertion($this->rules);

        foreach ($params as $param) {
            $this->addStatement($param[0], $param[1]);
        }

        return $this->statements;
    }

    /**
     * @return class-string<PHPStanRule<Node>>
     */
    abstract protected function getAssertionClassname(): string;

    /**
     * @param array<SelectorInterface> $targets
     */
    private function addStatement(SelectorInterface $subject, array $targets): void
    {
        $this->statements[] = [$subject, $targets];
    }

    /**
     * @param array<Rule> $rules
     * @return array<array{SelectorInterface, array<SelectorInterface>}>
     */
    private function extractCurrentAssertion(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            if ($rule->assertion === $this->getAssertionClassname()) {
                foreach ($rule->subjects as $selector) {
                    $result[] = [$selector, $rule->targets];
                }
            }
        }

        return $result;
    }
}
