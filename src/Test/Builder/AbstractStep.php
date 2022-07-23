<?php

declare(strict_types=1);

namespace PHPat\Test\Builder;

use PHPat\Rule\Assertion\Relation\ShouldExtend\ShouldExtend;
use PHPat\Rule\Assertion\Relation\ShouldImplement\ShouldImplement;
use PHPat\Rule\Assertion\Relation\ShouldNotConstruct\ShouldNotConstruct;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ShouldNotExtend;
use PHPat\Rule\Assertion\Relation\ShouldNotImplement\ShouldNotImplement;
use PHPat\Test\RelationRule;

abstract class AbstractStep implements Rule
{
    protected RelationRule $rule;

    final public function __construct(RelationRule $rule)
    {
        $this->rule = $rule;
    }

    final public function return(): RelationRule
    {
        return $this->rule;
    }
}
