<?php declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Test\Rule;
use PHPat\Test\TestParser;
use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

final class StatementBuilderFactory
{
    /** @var array<Rule> */
    private array $rules;

    public function __construct(TestParser $testParser)
    {
        $this->rules = $testParser();
    }

    /**
     * @param class-string<PHPStanRule<Node>> $assertion
     */
    public function create(string $assertion): StatementBuilder
    {
        return new StatementBuilder($assertion, $this->rules);
    }
}
