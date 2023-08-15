<?php declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Rule\Assertion\Declaration\DeclarationAssertion;
use PHPat\Rule\Assertion\Relation\RelationAssertion;
use PHPat\Test\RelationRule;
use PHPat\Test\Rule;
use PHPat\Test\TestParser;

final class StatementBuilderFactory
{
    /** @var array<Rule> */
    private array $rules;

    public function __construct(TestParser $testParser)
    {
        $this->rules = $testParser();
    }

    public function create(string $classname): StatementBuilder
    {
        if (is_a($classname, RelationAssertion::class, true)) {
            $statementBuilder = sprintf('%s\\RelationStatementBuilder', __NAMESPACE__);
            $rules = array_filter($this->rules, static fn ($rule) => is_a($rule, RelationRule::class, true));
        } elseif (is_a($classname, DeclarationAssertion::class, true)) {
            $statementBuilder = sprintf('%s\\DeclarationStatementBuilder', __NAMESPACE__);
            $rules = array_filter($this->rules, static fn ($rule) => is_a($rule, RelationRule::class, true));
        } else {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid statement builder', $classname));
        }

        return new $statementBuilder($classname, $rules);
    }
}
