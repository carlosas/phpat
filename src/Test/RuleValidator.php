<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Rule\Assertion\Relation\RelationAssertion;

final class RuleValidator implements RuleValidatorInterface
{
    public function validate(Rule $rule): void
    {
        if ($rule->getSubjects() === []) {
            throw new \Exception(sprintf('The PHPat rule %s has no subjects', $rule->getRuleName()));
        }

        $assertion = $rule->getAssertion();

        if ($assertion === null) {
            throw new \Exception(sprintf('The PHPat rule %s has no assertion', $rule->getRuleName()));
        }

        if (is_subclass_of($assertion, RelationAssertion::class) && $rule->getTargets() === []) {
            throw new \Exception(sprintf('The PHPat rule %s has no targets', $rule->getRuleName()));
        }
    }
}
