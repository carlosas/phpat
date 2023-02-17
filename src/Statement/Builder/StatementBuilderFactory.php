<?php

declare(strict_types=1);

namespace PHPat\Statement\Builder;

use InvalidArgumentException;
use PHPat\Rule\Assertion\Declaration\DeclarationAssertion;
use PHPat\Rule\Assertion\Relation\RelationAssertion;
use PHPat\Test\RelationRule;
use PHPat\Test\RuleWithName;
use PHPat\Test\TestParser;

class StatementBuilderFactory
{
    /** @var array<RuleWithName<RelationRule>> */
    private array $rules;

    public function __construct(TestParser $testParser)
    {
        $this->rules = $testParser();
    }

    public function create(string $classname): StatementBuilder
    {
        if (is_a($classname, RelationAssertion::class, true)) {
            /** @var class-string<RelationStatementBuilder> $statementBuilder */
            $statementBuilder = sprintf('%s\\RelationStatementBuilder', __NAMESPACE__);
        } elseif (is_a($classname, DeclarationAssertion::class, true)) {
            /** @var class-string<DeclarationStatementBuilder> $statementBuilder */
            $statementBuilder = sprintf('%s\\DeclarationStatementBuilder', __NAMESPACE__);
        } else {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid statement builder', $classname));
        }

        $lastSeparatorPos = strrpos($classname, '\\');
        $classnamePos     = $lastSeparatorPos !== false ? $lastSeparatorPos + 1 : 0;

        return new $statementBuilder($classname, $this->rules);
    }
}
