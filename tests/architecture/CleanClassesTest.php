<?php declare(strict_types=1);

namespace Tests\PHPat\architecture;

use PHPat\Selector\Selector;
use PHPat\Selector\SelectorPrimitive;
use PHPat\Test\Builder\AbstractStep;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use PHPat\Test\TestParser;

final class CleanClassesTest
{
    public function test_non_abstract_classes_are_final(): Rule
    {
        return PHPat::rule()
            ->classes(
                Selector::namespace('PHPat'),
                Selector::namespace('Tests\PHPat\architecture')
            )
            ->excluding(
                Selector::abstract(),
                Selector::interface(),
                Selector::extends(AbstractStep::class),
                Selector::classname(SelectorPrimitive::class),
                Selector::classname(TestParser::class)
            )
            ->shouldBeFinal()
        ;
    }
}
