<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class SelectorResolver
 *
 * @package PhpAT\Selector
 */
class SelectorResolver
{
    /**
     * @var ContainerBuilder 
     */
    private $container;

    /**
     * SelectorResolver constructor.
     *
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @param  SelectorInterface $selector
     * @return array
     */
    public function resolve(SelectorInterface $selector): array
    {
        foreach ($selector->getDependencies() as $dependency) {
            if ($this->container->has($dependency)) {
                $d[$dependency] = $this->container->get($dependency);
            }
        }

        $selector->injectDependencies($d ?? []);

        return $selector->select();
    }
}
