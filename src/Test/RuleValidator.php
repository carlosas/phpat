<?php

declare(strict_types=1);

namespace PHPat\Test;

use Exception;
use PHPat\Rule\Assertion\Relation\RelationAssertion;

class RuleValidator
{
    /**
     * @throws Exception
     */
    public function validate(Rule $rule): void
    {
        if ($rule->getSubjects() === []) {
            throw new Exception('One of your PHPat rules has no subjects');
        }
        if ($rule->getAssertion() === null) {
            throw new Exception('One of your PHPat rules has no assertion');
        }
        if (is_subclass_of($rule->getAssertion(), RelationAssertion::class) && $rule->getTargets() === []) {
            throw new Exception('One of your PHPat rules has no targets');
        }
    }
}
