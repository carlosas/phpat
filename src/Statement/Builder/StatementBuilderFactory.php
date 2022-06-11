<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Test\Rule;
use PHPat\Test\TestParser;

class StatementBuilderFactory
{
    /** @var array<Rule> */
    private array $rules;

    public function __construct(TestParser $testParser)
    {
        $this->rules = $testParser();
    }

    public function create(string $classname): StatementBuilder
    {
        $lastSeparatorPos = strrpos($classname, '\\');
        $classnamePos     = $lastSeparatorPos !== false ? $lastSeparatorPos + 1 : 0;

        /** @var class-string<StatementBuilder> $statementBuilder */
        $statementBuilder = sprintf(
            '%s\\%sStatementBuilder',
            __NAMESPACE__,
            substr($classname, $classnamePos)
        );

        return new $statementBuilder($this->rules);
    }
}
