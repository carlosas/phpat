<?php

namespace PHPat\Test\Builder;

use PHPat\Test\RelationRule;

interface Rule
{
    public function return(): RelationRule;
}
