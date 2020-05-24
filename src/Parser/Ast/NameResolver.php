<?php

namespace PhpAT\Parser\Ast;

use PhpParser\NameContext;
use PhpParser\NodeVisitor\NameResolver as PhpParserNameResolver;

class NameResolver extends PhpParserNameResolver
{
    public function __construct(
        NameContext $nameContext
    ) {
        parent::__construct();
        $this->nameContext = $nameContext;
    }
}
