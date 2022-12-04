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
    public function validate(RelationRule $rule): void
    {
        $this->validateRelationRule($rule);
    }

    private function validateRelationRule(RelationRule $rule): void
    {
        if ($rule->subjects === []) {
            throw new Exception('One of your PHPat rules has no subjects');
        }
        if ($rule->assertion === null) {
            throw new Exception('One of your PHPat rules has no assertion');
        }
        if (is_a($rule->assertion, RelationAssertion::class) && $rule->targets === []) {
            throw new Exception('One of your PHPat rules has no targets');
        }
    }
}
