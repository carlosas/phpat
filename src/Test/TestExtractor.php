<?php declare(strict_types=1);

namespace PHPArchiTest\Test;

interface TestExtractor
{
    public function execute(): ArchiTestCollection;
}
