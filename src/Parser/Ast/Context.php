<?php

namespace PhpAT\Parser\Ast;

use PhpParser\ErrorHandler\Throwing;
use PhpParser\NameContext;

class Context
{
    /** @var NameContext */
    public $class;
    /** @var \SplFileInfo */
    public $file;

    public function __construct()
    {
        $this->class = new NameContext(new Throwing());
    }

    public function setFileInfo(\SplFileInfo $fileInfo): void
    {
        $this->file = $fileInfo;
    }

    public function reset(): void
    {
        $this->class->startNamespace();
        $this->file = null;
    }
}
