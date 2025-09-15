<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Parser\PHPStanContainerWrapper;
use PHPat\ShouldNotHappenException;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionClass;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionEnum;
use PHPStan\DependencyInjection\Container;
use PHPStan\Reflection\ReflectionProvider;

final class TestExtractor implements TestExtractorInterface
{
    private const TEST_TAG = 'phpat.test';

    private PHPStanContainerWrapper $container;
    private ReflectionProvider $reflectionProvider;

    public function __construct(PHPStanContainerWrapper $container, ReflectionProvider $reflectionProvider)
    {
        $this->container = $container;
        $this->reflectionProvider = $reflectionProvider;
    }

    public function __invoke(): iterable
    {
        foreach ($this->container->getServicesByTag(self::TEST_TAG) as $test) {
            if (!is_object($test)) {
                throw new ShouldNotHappenException();
            }

            $reflectedTest = $this->reflectTest($test::class);
            if ($reflectedTest !== null) {
                yield $reflectedTest;
            }
        }
    }

    /**
     * @return null|ReflectionClass|ReflectionEnum
     */
    private function reflectTest(string $test)
    {
        if (!$this->reflectionProvider->hasClass($test)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($test)->getNativeReflection();
        if ($classReflection::class !== ReflectionClass::class) {
            return null;
        }

        return $classReflection;
    }
}
