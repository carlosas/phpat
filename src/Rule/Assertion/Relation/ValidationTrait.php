<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation;

use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

trait ValidationTrait
{
    /**
     * @param array<SelectorInterface> $targets
     * @param array<SelectorInterface> $targetExcludes
     * @param array<class-string> $nodes
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function applyShould(ClassReflection $subject, array $targets, array $targetExcludes, array $nodes): array
    {
        $errors = [];
        foreach ($targets as $target) {
            $targetFound = false;
            foreach ($nodes as $node) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
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
     * @param array<SelectorInterface> $targetExcludes
     * @param array<class-string> $nodes
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function applyShouldNot(ClassReflection $subject, array $targets, array $targetExcludes, array $nodes): array
    {
        $errors = [];
        foreach ($targets as $target) {
            foreach ($nodes as $node) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
                    $errors[] = RuleErrorBuilder::message($this->getMessage($subject->getName(), $node))->build();
                }
            }
        }

        return $errors;
    }

    /**
     * @param array<SelectorInterface> $targets
     * @param array<SelectorInterface> $targetExcludes
     * @param array<class-string> $nodes
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function applyCanOnly(ClassReflection $subject, array $targets, array $targetExcludes, array $nodes): array
    {
        $errors = [];
        foreach ($nodes as $node) {
            foreach ($targets as $target) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
                    continue 2;
                }
            }
            $errors[] = RuleErrorBuilder::message($this->getMessage($subject->getName(), $node))->build();
        }

        return $errors;
    }

    /**
     * @param class-string $classname
     * @param array<SelectorInterface> $targetExcludes
     */
    private function nodeMatchesTarget(string $classname, SelectorInterface $target, array $targetExcludes): bool
    {
        if (!$this->reflectionProvider->hasClass($classname)) {
            return false;
        }

        $class = $this->reflectionProvider->getClass($classname);

        if (!$target->matches($class)) {
            return false;
        }

        foreach ($targetExcludes as $exclude) {
            if ($exclude->matches($class)) {
                return false;
            }
        }

        return true;
    }
}
