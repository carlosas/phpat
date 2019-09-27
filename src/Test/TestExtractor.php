<?php

declare(strict_types=1);

namespace PhpAT\Test;

interface TestExtractor
{
    public function execute(): ArchitectureTestCollection;
}
