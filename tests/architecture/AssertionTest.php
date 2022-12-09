<?php

declare(strict_types=1);

namespace Tests\PHPat\architecture;

use PHPat\Rule\Assertion\Assertion;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class AssertionTest
{
    public function test_assertions_are_abstract(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::implements(Assertion::class))
            ->excluding(Selector::classname('/.*Rule$/', true))
            ->shouldBeAbstract();
    }
}
