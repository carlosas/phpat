<?php

namespace PhpAT\Statement\Builder;

use PhpAT\Test\TestParser;

class StatementBuilderFactory
{
    /** @var array<mixed> */
    private array $rules;

    public function __construct(TestParser $testParser)
    {
        $this->rules = $testParser();
    }

    public function create(string $classname): StatementBuilder
    {
        $statementBuilder = sprintf(
            '%s\\%sStatementBuilder',
            __NAMESPACE__,
            substr($classname, strrpos($classname, '\\') + 1)
        );

        return new $statementBuilder($this->rules);
    }
}
