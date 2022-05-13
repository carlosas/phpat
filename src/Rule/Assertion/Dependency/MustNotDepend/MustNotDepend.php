<?php

namespace PhpAT\Rule\Assertion\Dependency\MustNotDepend;

use PhpAT\Selector\Classname;
use PhpAT\Selector\Selector;
use PhpAT\Statement\Builder\StatementBuilderFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule as PHPStanRule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

abstract class MustNotDepend implements PHPStanRule
{
    protected const MESSAGE = '%s must not depend on %s';

    /** @var array<array{Selector, array<class-string>}> */
    protected array $statements;

    public function __construct(StatementBuilderFactory $statementBuilderFactory)
    {
        $this->statements = $statementBuilderFactory->create(self::class)->build();
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $target = $this->extractTargetClassName($node, $scope);
        if ($target === null) {
            return [];
        }

        if (!$this->ruleApplies($scope, $target)) {
            return [];
        }

        return $this->validateGetErrors($scope, $target);
    }

    /**
     * @return null|class-string
     */
    abstract protected function extractTargetClassName(Node $node, Scope $scope): ?string;

    /**
     * @param class-string $target
     */
    protected function ruleApplies(Scope $scope, string $target): bool
    {
        return !($scope->isInClass() && (new Classname($target))->matches($scope->getClassReflection()));
    }

    /**
     * @param class-string $target
     * @return array<RuleError>
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function validateGetErrors(Scope $scope, string $target): array
    {
        $subject = $scope->getClassReflection();
        $errors = [];

        foreach ($this->statements as [$selector, $ruleTargets]) {
            if (!$selector->matches($subject)) {
                continue;
            }

            foreach ($ruleTargets as $ruleTarget) {
                if ($ruleTarget === $target) {
                    $errors[] = RuleErrorBuilder::message(sprintf(self::MESSAGE, $subject->getName(), $target))
                        ->tip(static::class)
                        ->build();
                }
            }
        }
/*        if (in_array($target, $this->statements[$subject] ?? [], true)) {
            return [
                RuleErrorBuilder::message(sprintf(self::MESSAGE, $subject, $target))
                    ->tip(static::class)
                    ->build()
            ];
        }*/

        return $errors;
    }
}
