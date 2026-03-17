<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPStan\DependencyInjection\ContainerFactory;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPUnit\Framework\TestCase;

abstract class SelectorTestCase extends TestCase
{
    private static ?ReflectionProvider $reflectionProvider = null;

    protected function getReflectionProvider(): ReflectionProvider
    {
        if (self::$reflectionProvider === null) {
            require_once __DIR__.'/Fixtures.php';
            if (PHP_VERSION_ID >= 80200) {
                require_once __DIR__.'/ReadonlyFixture.php';
            }
            $factory = new ContainerFactory(getcwd());
            $container = $factory->create(sys_get_temp_dir(), [__DIR__.'/../../../ci/phpstan-phpat.neon'], []);
            self::$reflectionProvider = $container->getByType(ReflectionProvider::class);
        }

        return self::$reflectionProvider;
    }

    protected function getReflectionClass(string $className): ClassReflection
    {
        return $this->getReflectionProvider()->getClass($className);
    }
}
