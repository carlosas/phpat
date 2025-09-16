<?php declare(strict_types=1);

namespace PHPat\Parser;

use Psr\Container\ContainerExceptionInterface;

/**
 * Enhanced container exception with detailed error context for dependency injection failures.
 */
final class ContainerException extends \Exception implements ContainerExceptionInterface
{
    private string $serviceId;
    private ?string $context;

    public function __construct(
        string $serviceId,
        string $message,
        ?string $context = null,
        ?\Throwable $previous = null
    ) {
        $this->serviceId = $serviceId;
        $this->context = $context;

        $fullMessage = sprintf('Container error for service "%s": %s', $serviceId, $message);
        if ($context !== null) {
            $fullMessage .= sprintf(' Context: %s', $context);
        }

        parent::__construct($fullMessage, 0, $previous);
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    /**
     * Creates a service not found exception with helpful troubleshooting information.
     */
    public static function serviceNotFound(string $serviceId, ?string $additionalInfo = null): self
    {
        $message = sprintf(
            'Service "%s" not found in container. '
            .'This usually means the service is not registered in your PSR-11 container configuration.',
            $serviceId
        );

        $context = 'Check your container setup and ensure the service is properly registered. '
                  .'For dependency injection in test classes, make sure your container provides all required services.';

        if ($additionalInfo !== null) {
            $context .= ' '.$additionalInfo;
        }

        return new self($serviceId, $message, $context);
    }

    /**
     * Creates a service resolution failure exception with detailed error context.
     */
    public static function resolutionFailed(string $serviceId, \Throwable $cause, ?string $additionalInfo = null): self
    {
        $message = sprintf(
            'Failed to resolve service "%s": %s',
            $serviceId,
            $cause->getMessage()
        );

        $context = 'This error occurred while trying to resolve a dependency for your test class. '
                  .'Check your container configuration and ensure the service is properly configured.';

        if ($additionalInfo !== null) {
            $context .= ' '.$additionalInfo;
        }

        return new self($serviceId, $message, $context, $cause);
    }

    /**
     * Creates an exception for unsupported container operations.
     */
    public static function operationNotSupported(string $serviceId, string $operation): self
    {
        $message = sprintf('Operation "%s" not supported for service "%s"', $operation, $serviceId);

        $context = 'PHPStan 2.x removed container access, so this fallback container has limited functionality. '
                  .'To use full dependency injection features, configure a custom PSR-11 container. '
                  .'See the PHPat documentation for container configuration examples.';

        return new self($serviceId, $message, $context);
    }
}
