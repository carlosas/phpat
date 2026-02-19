<?php declare(strict_types=1);

namespace PHPat\Statement\Builder;

use PHPat\Test\TestParser;

final class StatementBuilderFactory
{
    /** @var array<\PHPat\Test\Rule> */
    private array $rules;

    public function __construct(TestParser $testParser)
    {
        $this->rules = $testParser();
    }

    /**
     * @param class-string<\PHPStan\Rules\Rule<\PhpParser\Node>> $classname
     */
    public function create(string $classname): StatementBuilderInterface
    {
        return new StatementBuilder($classname, $this->rules);
    }
}
