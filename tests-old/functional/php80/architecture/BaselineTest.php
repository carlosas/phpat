<?php

namespace Tests\PHPat\unit\php80\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php80\fixtures\SimpleClassOne;
use Tests\PHPat\unit\php80\fixtures\TwiceDependingClass;

class BaselineTest extends ArchitectureTest
{
    public function testBaselineMakesPassFailingTest(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(TwiceDependingClass::class))
            ->mustNotDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClassOne::class))
            ->build();
    }
}
