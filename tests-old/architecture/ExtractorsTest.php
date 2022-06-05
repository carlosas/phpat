<?php

namespace Tests\PHPat\Architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;

class ExtractorsTest extends ArchitectureTest
{
    public function testExtractorsDependOnRuleBuilder(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::implementInterface(\PHPat\Test\TestExtractor::class))
            ->mustDependOn()
            ->classesThat(SelectorInterface::haveClassName(\PHPat\Rule\TestParser::class))
            ->build();
    }
}
