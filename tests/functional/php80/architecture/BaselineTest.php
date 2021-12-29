<?php

namespace Tests\PhpAT\functional\php80\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php80\fixtures\TwiceDependingClass;
use Tests\PhpAT\functional\php80\fixtures\SimpleClassOne;

class BaselineTest extends ArchitectureTest
{
    public function testBaselineMakesPassFailingTest(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(TwiceDependingClass::class))
            ->mustNotDependOn()
            ->classesThat(Selector::haveClassName(SimpleClassOne::class))
            ->build();
    }
}
