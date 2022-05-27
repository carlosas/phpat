<?php

namespace PHPat\Rule\Assertion\ShouldImplement;

use PHPat\Rule\Assertion\Assertion;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleErrorBuilder;

abstract class ShouldImplement extends Assertion
{
    public function __construct(
        StatementBuilderFactory $statementBuilderFactory,
        ReflectionProvider $reflectionProvider
    ) {
        parent::__construct(__CLASS__, $statementBuilderFactory, $reflectionProvider);
    }

    protected function applyValidation(ClassReflection $subject, array $targets, array $nodes): array
    {
        $errors = [];
        foreach ($targets as $target) {
            $targetFound = false;
            foreach ($nodes as $node) {
                if (!$this->reflectionProvider->hasClass($node)) {
                    continue;
                }
                if ($target->matches($this->reflectionProvider->getClass($node))) {
                    $targetFound = true;
                    break;
                }
            }
            if (!$targetFound) {
                $errors[] = RuleErrorBuilder::message(
                    $this->getMessage($subject->getName(), $target->getName())
                )->build();
            }
        }

        return $errors;
    }

    protected function getMessage(string $subject, string $target): string
    {
        return sprintf('%s should implement %s', $subject, $target);
    }
}
