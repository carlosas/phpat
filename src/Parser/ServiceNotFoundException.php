<?php declare(strict_types=1);

namespace PHPat\Parser;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Enhanced service not found exception with detailed troubleshooting information.
 */
final class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface
{
    private string $serviceId;
    private array $availableServices;
    private ?string $suggestion;

    public function __construct(
        string $serviceId,
        array $availableServices = [],
        ?string $suggestion = null,
        ?\Throwable $previous = null
    ) {
        $this->serviceId = $serviceId;
        $this->availableServices = $availableServices;
        $this->suggestion = $suggestion;

        $message = $this->buildDetailedMessage();
        parent::__construct($message, 0, $previous);
    }

    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    public function getAvailableServices(): array
    {
        return $this->availableServices;
    }

    public function getSuggestion(): ?string
    {
        return $this->suggestion;
    }

    /**
     * Creates a service not found exception with a helpful suggestion based on similar service names.
     */
    public static function withSuggestion(
        string $serviceId,
        array $availableServices,
        ?string $suggestion = null
    ): self {
        // If no suggestion provided, try to find a similar service name
        if ($suggestion === null && !empty($availableServices)) {
            $suggestion = self::findSimilarServiceName($serviceId, $availableServices);
        }

        return new self($serviceId, $availableServices, $suggestion);
    }

    private function buildDetailedMessage(): string
    {
        $message = sprintf('Service "%s" not found in container.', $this->serviceId);

        // Add troubleshooting information
        $message .= "\n\nTroubleshooting steps:";
        $message .= "\n1. Check if the service is registered in your PSR-11 container configuration";
        $message .= "\n2. Verify the service ID matches exactly (case-sensitive)";
        $message .= "\n3. Ensure your container is properly configured in PHPat";

        // Add suggestion if available
        if ($this->suggestion !== null) {
            $message .= "\n\nSuggestion: ".$this->suggestion;
        }

        // Add available services if provided
        if (!empty($this->availableServices)) {
            $message .= "\n\nAvailable services in container:";
            foreach ($this->availableServices as $service) {
                $message .= "\n- ".$service;
            }
        } else {
            $message .= "\n\nNote: No services are available in the current container. "
                       .'This may indicate that no PSR-11 container is configured, '
                       .'or the container is empty.';
        }

        return $message;
    }

    /**
     * Finds a similar service name using simple string similarity.
     */
    private static function findSimilarServiceName(string $serviceId, array $availableServices): ?string
    {
        $bestMatch = null;
        $bestSimilarity = 0;

        foreach ($availableServices as $availableService) {
            $similarity = similar_text(strtolower($serviceId), strtolower($availableService));
            if ($similarity > $bestSimilarity && $similarity > strlen($serviceId) * 0.5) {
                $bestMatch = $availableService;
                $bestSimilarity = $similarity;
            }
        }

        return $bestMatch !== null ? sprintf('Did you mean "%s"?', $bestMatch) : null;
    }
}
