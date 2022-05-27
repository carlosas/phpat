<?php

namespace PHPat\Rule\Assertion\ShouldNotConstruct;

use PHPat\Rule\Assertion\Assertion;
use PHPat\Rule\Assertion\AssertionType;
use PHPat\Selector\SelectorInterface;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

abstract class ShouldNotConstruct extends Assertion
{
    public function __construct(
        StatementBuilderFactory $statementBuilderFactory,
        ReflectionProvider $reflectionProvider
    ) {
        parent::__construct(__CLASS__, $statementBuilderFactory, $reflectionProvider);
    }

    /**
     * @param array<SelectorInterface> $ruleTargets
     * @param array<class-string> $targets
     * @return array<RuleError>
     */
    protected function applyValidation(ClassReflection $subject, array $ruleTargets, array $targets): array
    {
        $errors = [];
        foreach ($ruleTargets as $ruleTarget) {
            foreach ($targets as $target) {
                if ($ruleTarget->matches($this->reflectionProvider->getClass($target))) {
                    $errors[] = RuleErrorBuilder::message($this->getMessage($subject->getName(), $target))->build();
                }
            }
        }

        return $errors;
    }

    protected function getMessage(string $subject, string $target): string
    {
        return sprintf('%s should not construct %s', $subject, $target);
    }
}
