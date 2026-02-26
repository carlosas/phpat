<?php declare(strict_types=1);

namespace PHPat\Test;

final class RuleValidator implements RuleValidatorInterface
{
    private const RELATION_TYPES = ['depend', 'extend', 'implement', 'include', 'construct', 'applyAttribute'];

    public function validate(Rule $rule): void
    {
        if ($rule->getSubjects() === []) {
            throw new \Exception(sprintf('The PHPat rule %s has no subjects', $rule->getRuleName()));
        }

        $assertionType = $rule->getAssertionType();

        if ($assertionType === null) {
            throw new \Exception(sprintf('The PHPat rule %s has no assertion', $rule->getRuleName()));
        }

        if ($rule->getConstraint() === null) {
            throw new \Exception(sprintf('The PHPat rule %s has no constraint', $rule->getRuleName()));
        }

        if (in_array($assertionType, self::RELATION_TYPES, true) && $rule->getTargets() === []) {
            throw new \Exception(sprintf('The PHPat rule %s has no targets', $rule->getRuleName()));
        }
    }
}
