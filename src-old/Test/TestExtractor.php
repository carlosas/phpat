<?php

declare(strict_types=1);

namespace PHPatOld\Test;

interface TestExtractor
{
    public function execute(): ArchitectureTestCollection;
}
