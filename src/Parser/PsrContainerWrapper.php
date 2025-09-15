<?php declare(strict_types=1);

namespace PHPat\Parser;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class PsrContainerWrapper implements ContainerInterface
{
    private PHPStanContainerWrapper $container;

    public function __construct(PHPStanContainerWrapper $container)
    {
        $this->container = $container;
    }

    public function get(string $id)
    {
        if (!$this->container->hasService($id)) {
            throw new class(sprintf('Service "%s" not found.', $id)) extends \Exception implements NotFoundExceptionInterface {};
        }

        return $this->container->getService($id);
    }

    public function has(string $id): bool
    {
        return $this->container->hasService($id);
    }
}
