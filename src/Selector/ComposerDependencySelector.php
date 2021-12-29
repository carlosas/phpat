<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Rule\Event\BaselineObsoleteEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class ComposerDependencySelector implements SelectorInterface
{
    private const DEPENDENCIES = [
        EventDispatcherInterface::class
    ];

    private EventDispatcherInterface $eventDispatcher;
    private ?ReferenceMap $map = null;
    private bool $devMode;
    private string $packageAlias;

    public function __construct(string $packageAlias, bool $devMode = false)
    {
        $this->packageAlias = $packageAlias;
        $this->devMode      = $devMode;
    }

    public function getDependencies(): array
    {
        return self::DEPENDENCIES;
    }

    public function injectDependencies(array $dependencies)
    {
        $this->eventDispatcher = $dependencies[EventDispatcherInterface::class];
    }

    public function setReferenceMap(ReferenceMap $map): void
    {
        $this->map = $map;
    }

    /** @return array<ClassLike> */
    public function select(): array
    {
        $package = $this->map->getComposerPackages()[$this->packageAlias] ?? null;

        if (!$package instanceof \PhpAT\Parser\Ast\ComposerPackage) {
            $this->eventDispatcher->dispatch(
                new BaselineObsoleteEvent("Package " . $this->packageAlias . "not found in configuration")
            );

            return [];
        }

        return $this->devMode ? $package->getDevDependencies() : $package->getDependencies();
    }

    public function getParameter(): string
    {
        return sprintf('%s (%s)', $this->packageAlias, $this->devMode ? 'true' : 'false');
    }
}
