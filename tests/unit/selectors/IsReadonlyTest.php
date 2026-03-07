<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsReadonly;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsReadonly
 */
class IsReadonlyTest extends SelectorTestCase
{
    public function testMatchesReadonly(): void
    {
        if (PHP_VERSION_ID < 80200) {
            $this->markTestSkipped('Readonly classes are only available in PHP 8.2+');
        }

        require_once __DIR__.'/ReadonlyFixture.php';

        $selector = new IsReadonly();
        $classReflection = $this->getReflectionClass(ReadonlyFixture::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonReadonly(): void
    {
        $selector = new IsReadonly();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
