<?php

namespace PhpAT\Rule\Assertion\Dependency\MustNotDepend;

use PhpAT\Selector\Classname;
use PhpAT\Selector\SelectorInterface;
use PhpAT\Statement\Builder\StatementBuilderFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule as PHPStanRule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

abstract class MustNotDepend implements PHPStanRule
{
    /** @var array<array{SelectorInterface, array<SelectorInterface>}> */
    protected array $statements;

    private ReflectionProvider $reflectionProvider;

    public function __construct(StatementBuilderFactory $statementBuilderFactory, ReflectionProvider $reflectionProvider)
    {
        $this->statements = $statementBuilderFactory->create(__CLASS__)->build();
        $this->reflectionProvider = $reflectionProvider;
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
        $target = $this->reflectionProvider->getClass($target);
        $errors = [];

        foreach ($this->statements as [$selector, $ruleTargets]) {
            if (!$selector->matches($subject)) {
                continue;
            }

            foreach ($ruleTargets as $ruleTarget) {
                if ($ruleTarget->matches($target)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf('%s must not depend on %s', $subject->getName(), $target->getName())
                    )->build();
                }
            }
        }

        return $errors;
    }
}
