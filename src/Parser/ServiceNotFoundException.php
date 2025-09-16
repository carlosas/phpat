<?php declare(strict_types=1);

namespace PHPat\Parser;

use Psr\Container\NotFoundExceptionInterface;

final class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface
{
    public function __construct(string $serviceId) {
        $message = sprintf('Service "%s" not found in container.', $serviceId);

        parent::__construct($message, 0);
    }
}
