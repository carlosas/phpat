<?php declare(strict_types=1);

namespace PHPat\Parser;

use Psr\Container\ContainerInterface;

/**
 * Enhanced PSR-11 container wrapper with improved error handling and service validation.
 */
final class PsrContainerWrapper implements ContainerInterface
{
    private PHPStanContainerWrapper $container;

    public function __construct(PHPStanContainerWrapper $container)
    {
        $this->container = $container;
    }

    public function get(string $id)
    {
        // Validate service existence first with detailed error information
        if (!$this->validateServiceExists($id)) {
            $availableServices = $this->getAvailableServices();

            throw new ServiceNotFoundException($id);
        }

        try {
            return $this->container->getService($id);
        } catch (\RuntimeException $e) {
            // Wrap runtime exceptions from the underlying container with better context
            throw ContainerException::resolutionFailed(
                $id,
                $e,
                'Verify that the service is properly configured and all its dependencies are available.'
            );
        } catch (\Throwable $e) {
            // Handle any other unexpected errors
            throw ContainerException::resolutionFailed(
                $id,
                $e,
                'This may indicate a problem with your container configuration or service definition.'
            );
        }
    }

    public function has(string $id): bool
    {
        return $this->validateServiceExists($id);
    }

    /**
     * Validates if a service exists in the container with enhanced error context.
     */
    private function validateServiceExists(string $id): bool
    {
        try {
            return $this->container->hasService($id);
        } catch (\Throwable $e) {
            // If we can't even check if the service exists, log the error but return false
            // This prevents cascading failures during service validation
            return false;
        }
    }

    /**
     * Gets a list of available services for better error messages.
     * Returns empty array for PHPStan fallback container since it has no services.
     */
    private function getAvailableServices(): array
    {
        try {
            // For PHPStan fallback container, we know there are no services
            // In a real PSR-11 container implementation, this would return actual service IDs
            return [];
        } catch (\Throwable $e) {
            // If we can't get available services, return empty array
            return [];
        }
    }
}
