<?php

namespace PHPat\Rule\Assertion;

use PHPat\Selector\Classname;
use PHPat\Selector\SelectorInterface;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule as PHPStanRule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

abstract class Assertion implements PHPStanRule
{
    /** @var array<array{SelectorInterface, array<SelectorInterface>}> */
    protected array $statements;

    protected ReflectionProvider $reflectionProvider;

    /**
     * @param class-string<Assertion> $assertion
     */
    public function __construct(
        string $assertion,
        StatementBuilderFactory $statementBuilderFactory,
        ReflectionProvider $reflectionProvider
    ) {
        $this->statements         = $statementBuilderFactory->create($assertion)->build();
        $this->reflectionProvider = $reflectionProvider;
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $targets = $this->extractNodeClassNames($node, $scope);

        if (!$this->ruleApplies($scope, $targets)) {
            return [];
        }

        return $this->validateGetErrors($scope, $targets);
    }

    /**
     * @return iterable<class-string>
     */
    abstract protected function extractNodeClassNames(Node $node, Scope $scope): iterable;

    /**
     * @param class-string $subject
     * @param class-string $target
     */
    abstract protected function getMessage(string $subject, string $target): string;

    /**
     * @param iterable<class-string> $targets
     */
    protected function ruleApplies(Scope $scope, iterable $targets): bool
    {
        if (empty($targets) || !($scope->isInClass())) {
            return false;
        }

        foreach ($targets as $target) {
            if (!(new Classname($target))->matches($scope->getClassReflection())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param iterable<class-string> $targets
     * @throws \PHPStan\ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function validateGetErrors(Scope $scope, iterable $targets): array
    {
        $subject = $scope->getClassReflection();
        $errors  = [];

        foreach ($this->statements as [$selector, $ruleTargets]) {
            if ($subject->isBuiltin() || !$selector->matches($subject)) {
                continue;
            }

            foreach ($ruleTargets as $ruleTarget) {
                foreach ($targets as $target) {
                    if ($ruleTarget->matches($this->reflectionProvider->getClass($target))) {
                        $errors[] = RuleErrorBuilder::message($this->getMessage($subject->getName(), $target))->build();
                    }
                }
            }
        }

        return $errors;
    }
}
