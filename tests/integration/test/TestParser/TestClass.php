<?php

declare(strict_types=1);

namespace Tests\PHPat\integration\test\TestParser;

use PHPat\Selector\Selector;
use PHPat\Test\Attributes\TestRule;
use PHPat\Test\PHPat;
use PHPat\Test\Builder\Rule;

final class TestClass
{
    /** @return iterable<Rule> */
    public function test_rules_from_iterator(): iterable
    {
        yield 'one' => PHPat::rule()->classes(Selector::classname('1'));
        yield 'two' => PHPat::rule()->classes(Selector::classname('2'));
    }

    public function test_rule(): Rule
    {
        return PHPat::rule()->classes(Selector::classname('3'));
    }

    #[TestRule]
    public function test_rule_from_attribute(): Rule
    {
        return PHPat::rule()->classes(Selector::classname('4'));
    }
}