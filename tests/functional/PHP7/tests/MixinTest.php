<?php

namespace Tests\PhpAT\functional\PHP7\tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class MixinTest extends ArchitectureTest
{
    public function testSimpleTraitInclusion(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Mixin/IncludeTrait.php'))
            ->shouldInclude()
            ->classesThat(Selector::havePathname('SimpleTrait.php'))
            ->build();
    }

    public function testMultipleTraitsInclusion(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Mixin/IncludeMultipleTraits.php'))
            ->shouldInclude()
            ->classesThat(Selector::havePathname('SimpleTrait.php'))
            ->andClassesThat(Selector::havePathname('Mixin/MixinNamespaceSimpleTrait.php'))
            ->build();
    }
}
