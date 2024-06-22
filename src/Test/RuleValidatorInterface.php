<?php declare(strict_types=1);

namespace PHPat\Test;

interface RuleValidatorInterface
{
    /**
     * @throws \Exception
     */
    public function validate(Rule $rule): void;
}
