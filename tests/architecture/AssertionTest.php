<?php

declare(strict_types=1);

namespace Tests\PHPat\architecture;

use PHPat\Parser\TypeNodeParser;
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

    public function test_rules_are_not_abstract(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname('/.*\\\Assertion\\\.*Rule$/', true))
            ->shouldNotBeAbstract();
    }

    public function test_rules_dependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::implements(Assertion::class))
            ->excluding(Selector::abstract())
            ->canOnlyDependOn()
            ->classes(
                Selector::namespace('PhpParser'),
                Selector::namespace('PHPStan'),
                Selector::classname(TypeNodeParser::class),
            );
    }
}
