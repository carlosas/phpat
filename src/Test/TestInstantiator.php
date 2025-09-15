<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Configuration;
use PHPat\ShouldNotHappenException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class TestInstantiator implements TestInstantiatorInterface
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param \ReflectionClass<object> $class
     */
    public function instantiate(\ReflectionClass $class): object
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
        $container = $this->configuration->getContainer();

        foreach ($parameters as $parameter) {
            $arguments[] = $this->resolveParameter($parameter, $container, $class);
        }

        return $class->newInstanceArgs($arguments);
    }

    /**
     * @param \ReflectionClass<object> $testClass
     */
    private function resolveParameter(
        \ReflectionParameter $parameter,
        ?ContainerInterface $container,
        \ReflectionClass $testClass
    ): mixed {
        // If no container is available, handle optional parameters or fail
        if ($container === null) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw new ShouldNotHappenException(
                sprintf(
                    'Cannot resolve parameter "%s" in test class "%s": no container configured and parameter is not optional',
                    $parameter->getName(),
                    $testClass->getName()
                )
            );
        }

        // Get parameter type
        $type = $parameter->getType();

        if ($type === null) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw new ShouldNotHappenException(
                sprintf(
                    'Cannot resolve parameter "%s" in test class "%s": parameter has no type hint',
                    $parameter->getName(),
                    $testClass->getName()
                )
            );
        }

        // Handle union types (not supported for DI)
        if ($type instanceof \ReflectionUnionType) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw new ShouldNotHappenException(
                sprintf(
                    'Cannot resolve parameter "%s" in test class "%s": union types are not supported for dependency injection',
                    $parameter->getName(),
                    $testClass->getName()
                )
            );
        }

        // Handle named types
        if ($type instanceof \ReflectionNamedType) {
            $typeName = $type->getName();

            // Skip built-in types
            if ($type->isBuiltin()) {
                if ($parameter->isOptional()) {
                    return $parameter->getDefaultValue();
                }

                throw new ShouldNotHappenException(
                    sprintf(
                        'Cannot resolve parameter "%s" in test class "%s": built-in type "%s" cannot be resolved from container',
                        $parameter->getName(),
                        $testClass->getName(),
                        $typeName
                    )
                );
            }

            // Try to resolve from container
            try {
                return $container->get($typeName);
            } catch (NotFoundExceptionInterface $e) {
                // Service not found, check if parameter is optional
                if ($parameter->isOptional()) {
                    return $parameter->getDefaultValue();
                }

                throw new ShouldNotHappenException(
                    sprintf(
                        'Cannot resolve parameter "%s" of type "%s" in test class "%s": service not found in container. %s',
                        $parameter->getName(),
                        $typeName,
                        $testClass->getName(),
                        $e->getMessage()
                    )
                );
            } catch (ContainerExceptionInterface $e) {
                throw new ShouldNotHappenException(
                    sprintf(
                        'Error resolving parameter "%s" of type "%s" in test class "%s": %s',
                        $parameter->getName(),
                        $typeName,
                        $testClass->getName(),
                        $e->getMessage()
                    )
                );
            }
        }

        // Fallback for other reflection types
        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        throw new ShouldNotHappenException(
            sprintf(
                'Cannot resolve parameter "%s" in test class "%s": unsupported parameter type',
                $parameter->getName(),
                $testClass->getName()
            )
        );
    }
}
