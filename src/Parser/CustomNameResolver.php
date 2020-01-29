<?php

namespace PhpAT\Parser;

use PhpParser\ErrorHandler;
use PhpParser\NameContext;
use PhpParser\NodeVisitor\NameResolver;

class CustomNameResolver extends NameResolver
{
    public function __construct(
        NameContext &$nameContext = null,
        ErrorHandler $errorHandler = null,
        array $options = []
    ) {
        parent::__construct($errorHandler, $options);
        $this->nameContext = $nameContext ?? new NameContext($errorHandler ?? new ErrorHandler\Throwing());
    }
}
