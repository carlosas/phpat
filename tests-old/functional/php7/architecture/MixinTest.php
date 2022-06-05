<?php

namespace Tests\PHPat\unit\php7\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;

class MixinTest extends ArchitectureTest
{
    public function testSimpleTraitInclusion(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Mixin/IncludeTrait.php'))
            ->mustInclude()
            ->classesThat(SelectorInterface::havePath('SimpleTrait.php'))
            ->build();
    }

    public function testMultipleTraitsInclusion(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Mixin/IncludeMultipleTraits.php'))
            ->mustInclude()
            ->classesThat(SelectorInterface::havePath('SimpleTrait.php'))
            ->andClassesThat(SelectorInterface::havePath('Mixin/MixinNamespaceSimpleTrait.php'))
            ->build();
    }
}
