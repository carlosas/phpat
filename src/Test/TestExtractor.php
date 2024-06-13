<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\ShouldNotHappenException;
use PHPStan\DependencyInjection\Container;
use PHPStan\Reflection\ReflectionProvider;

final class TestExtractor implements TestExtractorInterface
{
    private const TEST_TAG = 'phpat.test';

    private Container $container;
    private ReflectionProvider $reflectionProvider;

    public function __construct(Container $container, ReflectionProvider $reflectionProvider)
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

            $reflectedTest = $this->reflectTest(get_class($test));
            if ($reflectedTest !== null) {
                yield $reflectedTest;
            }
        }
    }

    /**
     * @param  class-string                  $test
     * @return null|\ReflectionClass<object>
     */
    private function reflectTest(string $test): ?\ReflectionClass
    {
        if (!$this->reflectionProvider->hasClass($test)) {
            return null;
        }

        return $this->reflectionProvider->getClass($test)->getNativeReflection();
    }
}
