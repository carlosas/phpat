<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\ShouldNotHappenException;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionClass;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionEnum;

final class TestInstantiator implements TestInstantiatorInterface
{
    private DependencyResolverInterface $dependencyResolver;

    public function __construct(DependencyResolverInterface $dependencyResolver)
    {
        $this->dependencyResolver = $dependencyResolver;
    }

    /**
     * @param ReflectionClass|\ReflectionClass<object>|ReflectionEnum $class
     */
    public function instantiate(ReflectionClass|\ReflectionClass|ReflectionEnum $class): object
    {
        $constructor = $class->getConstructor();

        // Handle classes without constructors
        if ($constructor === null) {
            return $class->newInstance();
        }

        $parameters = $constructor->getParameters();

        // Handle constructors without parameters
        if (empty($parameters)) {
            return $class->newInstance();
        }

        // Resolve constructor dependencies
        $arguments = [];

        foreach ($parameters as $parameter) {
            try {
                $arguments[] = $this->dependencyResolver->resolve($parameter);
            } catch (ShouldNotHappenException $e) {
                throw new ShouldNotHappenException(
                    sprintf(
                        'Cannot instantiate test class "%s": %s',
                        $class->getName(),
                        $e->getMessage()
                    )
                );
            }
        }

        return $class->newInstanceArgs($arguments);
    }
}
