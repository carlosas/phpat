<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\All;

/**
 * @internal
 *
 * @covers \PHPat\Selector\All
 */
class AllTest extends SelectorTestCase
{
    public function testGetName(): void
    {
        $selector = new All();

        $this->assertEquals('-all classes-', $selector->getName());
    }

    /**
     * @dataProvider getClassNames
     */
    public function testMatches(string $className): void
    {
        $selector = new All();
        $classReflection = $this->getReflectionClass($className);

        self::assertTrue($selector->matches($classReflection));
    }

    public static function getClassNames(): array
    {
        return [
            [DummyClassValid::class],
            ['stdClass'],
        ];
    }
}
