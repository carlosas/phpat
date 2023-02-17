<?php

declare(strict_types=1);

namespace PHPat\Test;

use Exception;
use PHPat\Rule\Assertion\Relation\RelationAssertion;

class RuleValidator
{
    /**
     * @template T of Rule
     * @param RuleWithName<T> $rule
     * @throws Exception
     */
    public function validate(RuleWithName $rule): void
    {
        if ($rule->getRule()->getSubjects() === []) {
            throw new Exception('One of your PHPat rules has no subjects');
        }

        $assertion = $rule->getRule()->getAssertion();

        if ($assertion === null) {
            throw new Exception('One of your PHPat rules has no assertion');
        }

        if (is_subclass_of($assertion, RelationAssertion::class) && $rule->getRule()->getTargets() === []) {
            throw new Exception('One of your PHPat rules has no targets');
        }
    }
}
