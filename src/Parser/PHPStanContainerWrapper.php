<?php declare(strict_types=1);

namespace PHPat\Parser;

/**
 * Fallback container wrapper for PHPStan compatibility.
 * Note: PHPStan 2.x removed the Container interface, so this is now a simple fallback container.
 * For actual dependency injection, use custom PSR-11 containers via configuration.
 */
final class PHPStanContainerWrapper
{
    public function __construct()
    {
        // No longer depends on PHPStan's internal container due to PHPStan 2.x compatibility
    }

    public function getService(string $serviceName): object
    {
        throw new \RuntimeException(
            sprintf(
                'Service "%s" not found. PHPStan 2.x removed container access. Use a custom PSR-11 container instead.',
                $serviceName
            )
        );
    }

    public function hasService(string $serviceName): bool
    {
        return false; // No services available in fallback container
    }

    public function getByType(string $className, bool $throw = true): null
    {
        if ($throw) {
            throw new \RuntimeException(
                sprintf(
                    'Service of type "%s" not found. PHPStan 2.x removed container access. Use a custom PSR-11 container instead.',
                    $className
                )
            );
        }

        return null;
    }

    public function findServiceNamesByType(string $className): array
    {
        return []; // No services available in fallback container
    }

    public function getServicesByTag(string $tagName): array
    {
        return []; // No services available in fallback container
    }

    public function getParameters(): array
    {
        return []; // No parameters available in fallback container
    }

    public function hasParameter(string $parameterName): bool
    {
        return false; // No parameters available in fallback container
    }

    public function getParameter(string $parameterName): never
    {
        throw new \RuntimeException(
            sprintf(
                'Parameter "%s" not found. PHPStan 2.x removed container access. Use a custom PSR-11 container instead.',
                $parameterName
            )
        );
    }
}
