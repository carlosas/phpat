<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Configuration;
use PHPat\ShouldNotHappenException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class DependencyResolver implements DependencyResolverInterface
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function resolve(\ReflectionParameter $parameter): mixed
    {
        $container = $this->configuration->getContainer();

        // If no container is available, handle optional parameters or fail
        if ($container === null) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw new ShouldNotHappenException(
                sprintf(
                    'Cannot resolve parameter "%s": no container configured and parameter is not optional',
                    $parameter->getName()
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
                    'Cannot resolve parameter "%s": parameter has no type hint',
                    $parameter->getName()
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
                    'Cannot resolve parameter "%s": union types are not supported for dependency injection',
                    $parameter->getName()
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
                        'Cannot resolve parameter "%s": built-in type "%s" cannot be resolved from container',
                        $parameter->getName(),
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
                        'Cannot resolve parameter "%s" of type "%s": service not found in container. Available services can be checked using $container->has("%s"). Original error: %s',
                        $parameter->getName(),
                        $typeName,
                        $typeName,
                        $e->getMessage()
                    )
                );
            } catch (ContainerExceptionInterface $e) {
                throw new ShouldNotHappenException(
                    sprintf(
                        'Error resolving parameter "%s" of type "%s": %s',
                        $parameter->getName(),
                        $typeName,
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
                'Cannot resolve parameter "%s": unsupported parameter type',
                $parameter->getName()
            )
        );
    }

    public function canResolve(\ReflectionParameter $parameter): bool
    {
        $container = $this->configuration->getContainer();

        // If no container is available, only optional parameters can be resolved
        if ($container === null) {
            return $parameter->isOptional();
        }

        // Get parameter type
        $type = $parameter->getType();

        // Parameters without type hints can only be resolved if they're optional
        if ($type === null) {
            return $parameter->isOptional();
        }

        // Union types are not supported for DI, only optional ones can be resolved
        if ($type instanceof \ReflectionUnionType) {
            return $parameter->isOptional();
        }

        // Handle named types
        if ($type instanceof \ReflectionNamedType) {
            $typeName = $type->getName();

            // Built-in types can only be resolved if they're optional
            if ($type->isBuiltin()) {
                return $parameter->isOptional();
            }

            // Check if the service exists in the container or if parameter is optional
            try {
                return $container->has($typeName) || $parameter->isOptional();
            } catch (ContainerExceptionInterface $e) {
                // If there's an error checking the container, fall back to optional check
                return $parameter->isOptional();
            }
        }

        // For other reflection types, only optional parameters can be resolved
        return $parameter->isOptional();
    }
}
