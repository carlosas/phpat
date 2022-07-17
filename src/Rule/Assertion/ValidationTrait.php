<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion;

use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

trait ValidationTrait
{
    /**
     * @param array<SelectorInterface> $targets
     * @param array<class-string> $nodes
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function applyShould(ClassReflection $subject, array $targets, array $nodes): array
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

    /**
     * @param array<SelectorInterface> $targets
     * @param array<class-string> $nodes
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function applyShouldNot(ClassReflection $subject, array $targets, array $nodes): array
    {
        $errors = [];
        foreach ($targets as $target) {
            foreach ($nodes as $node) {
                if (!$this->reflectionProvider->hasClass($node)) {
                    continue;
                }
                if ($target->matches($this->reflectionProvider->getClass($node))) {
                    $errors[] = RuleErrorBuilder::message($this->getMessage($subject->getName(), $node))->build();
                }
            }
        }

        return $errors;
    }
}
