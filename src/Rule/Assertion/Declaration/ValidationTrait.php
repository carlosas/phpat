<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration;

use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

trait ValidationTrait
{
    /**
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function applyShould(ClassReflection $subject, bool $meetsDeclaration): array
    {
        $errors = [];

        if (!$meetsDeclaration) {
            $errors[] = RuleErrorBuilder::message(
                $this->getMessage($subject->getName())
            )->build();
        }

        return $errors;
    }

    /**
     * @throws ShouldNotHappenException
     * @return array<RuleError>
     */
    protected function applyShouldNot(ClassReflection $subject, bool $meetsDeclaration): array
    {
        $errors = [];

        if ($meetsDeclaration) {
            $errors[] = RuleErrorBuilder::message(
                $this->getMessage($subject->getName())
            )->build();
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
