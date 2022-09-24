<?php

declare(strict_types=1);

namespace PHPat\Test;

use Exception;

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
        if ($rule->targets === []) {
            throw new Exception('One of your PHPat rules has no targets');
        }
    }
}
