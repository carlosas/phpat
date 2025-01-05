<?php declare(strict_types=1);

namespace PHPat\Test;

use Psr\Container\ContainerInterface;

final class ContainerAwarePHPat
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
