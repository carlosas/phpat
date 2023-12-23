<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration;

use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

trait ValidationTrait
{
    /**
     * @param  array<string>            $tips
     * @param  array<string, mixed>     $params
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    protected function applyShould(string $ruleName, ClassReflection $subject, bool $meetsDeclaration, array $tips, array $params): array
    {
        $errors = [];

        if (!$meetsDeclaration) {
            $ruleError = RuleErrorBuilder::message(
                $this->getMessage($ruleName, $subject->getName(), $params)
            );

            foreach ($tips as $tip) {
                $ruleError->addTip($tip);
            }
            $errors[] = $ruleError->build();
        }

        return $errors;
    }

    /**
     * @param  array<string>            $tips
     * @param  array<string, mixed>     $params
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    protected function applyShouldNot(string $ruleName, ClassReflection $subject, bool $meetsDeclaration, array $tips, array $params): array
    {
        $errors = [];

        if ($meetsDeclaration) {
            $ruleError = RuleErrorBuilder::message(
                $this->getMessage($ruleName, $subject->getName(), $params)
            );

            foreach ($tips as $tip) {
                $ruleError->addTip($tip);
            }
            $errors[] = $ruleError->build();
        }

        return $errors;
    }

    /**
     * @param class-string             $classname
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
