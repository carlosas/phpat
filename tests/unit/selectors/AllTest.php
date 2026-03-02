<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\All;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\All
 */
class AllTest extends TestCase
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
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn($className);

        self::assertTrue($selector->matches($classReflection));
    }

    public static function getClassNames(): array
    {
        return [
            ['App\User'],
            ['stdClass'],
            [''],
        ];
    }
}
