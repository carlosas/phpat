<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\ClassIncludes;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassIncludes
 */
class ClassIncludesTest extends SelectorTestCase
{
    public function testMatchesTrait(): void
    {
        $selector = new ClassIncludes(TraitDummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(HasTraitsDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesTraitInParent(): void
    {
        $selector = new ClassIncludes(TraitDummyClassValid::class, false);
        $classReflection = $this->getReflectionClass(ExtendsHasTraitsDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }
}
