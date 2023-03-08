<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Test\Builder\SubjectStep;

class PHPat
{
    public static function rule(): SubjectStep
    {
        // get architecture rule name from stack trace ( e.g. test_xxxx )
        $ruleName = debug_backtrace()[1]['function'] ?? '';

        $rule           = new RelationRule();
        $rule->ruleName = $ruleName;
        return new SubjectStep($rule);
    }
}
