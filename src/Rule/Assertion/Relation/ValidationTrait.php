<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation;

use PHPat\Selector\SelectorInterface;
use PHPat\ShouldNotHappenException;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

trait ValidationTrait
{
    /**
     * @param  array<SelectorInterface> $targets
     * @param  array<SelectorInterface> $targetExcludes
     * @param  array<class-string>      $nodes
     * @param  array<string>            $tips
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    protected function applyShould(string $ruleName, ClassReflection $subject, array $targets, array $targetExcludes, array $nodes, array $tips): array
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
                $ruleError = RuleErrorBuilder::message($this->getMessage($ruleName, $subject->getName(), $target->getName()));
                foreach ($tips as $tip) {
                    $ruleError->addTip($tip);
                }
                $errors[] = $ruleError->build();
            }
        }

        return $errors;
    }

    /**
     * @param  array<SelectorInterface> $targets
     * @param  array<SelectorInterface> $targetExcludes
     * @param  array<class-string>      $nodes
     * @param  array<string>            $tips
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    protected function applyShouldNot(string $ruleName, ClassReflection $subject, array $targets, array $targetExcludes, array $nodes, array $tips): array
    {
        $errors = [];
        foreach ($targets as $target) {
            foreach ($nodes as $node) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
                    $ruleError = RuleErrorBuilder::message($this->getMessage($ruleName, $subject->getName(), $node));
                    foreach ($tips as $tip) {
                        $ruleError->addTip($tip);
                    }
                    $errors[] = $ruleError->build();
                }
            }
        }

        return $errors;
    }

    /**
     * @param  array<SelectorInterface> $targets
     * @param  array<SelectorInterface> $targetExcludes
     * @param  array<class-string>      $nodes
     * @param  array<string>            $tips
     * @return array<RuleError>
     * @throws ShouldNotHappenException
     */
    protected function applyCanOnly(string $ruleName, ClassReflection $subject, array $targets, array $targetExcludes, array $nodes, array $tips): array
    {
        $errors = [];
        foreach ($nodes as $node) {
            foreach ($targets as $target) {
                if ($this->nodeMatchesTarget($node, $target, $targetExcludes)) {
                    continue 2;
                }
            }
            $ruleError = RuleErrorBuilder::message($this->getMessage($ruleName, $subject->getName(), $node));
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
