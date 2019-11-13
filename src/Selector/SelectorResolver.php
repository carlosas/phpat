<?php declare(strict_types=1);

namespace PhpAT\Selector;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class SelectorResolver
{
    /** @var ContainerBuilder */
    private $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /** @return \SplFileInfo[] */
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
