<?php

namespace Tests\PhpAT\functional\php8\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php8\fixtures\TwiceDependingClass;
use Tests\PhpAT\functional\php8\fixtures\SimpleClassOne;

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
