<?php

use PhpAT\Rule\Type\Inheritance;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class ExtractorsTest extends ArchitectureTest
{
    public function testExtractorsExtendAbstractExtractor(): Rule
    {
        return $this->newRule
            ->filesLike('Parser/*Extractor.php')
            ->excluding('Parser/AbstractExtractor.php')
            ->shouldHave(Inheritance::class)
            ->withParams([
                'file' => 'Parser/AbstractExtractor.php'
            ])
            ->build();
    }
}
