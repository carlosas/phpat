<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\App\Event\ErrorEvent;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ComposerPackage;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\ClassLike;

class ComposerDependencySelector implements SelectorInterface
{
    private const DEPENDENCIES = [
        EventDispatcher::class
    ];

    /** @var EventDispatcher */
    private $eventDispatcher;
    /** @var ReferenceMap */
    private $map;
    /** @var bool */
    private $devMode;
    /** @var string */
    private $packageAlias;

    public function __construct(string $packageAlias, bool $devMode = false)
    {
        $this->packageAlias = $packageAlias;
        $this->devMode = $devMode;
    }

    public function getDependencies(): array
    {
        return self::DEPENDENCIES;
    }

    public function injectDependencies(array $dependencies)
    {
        $this->eventDispatcher = $dependencies[EventDispatcher::class];
    }

    /** @param ReferenceMap $map */
    public function setReferenceMap(ReferenceMap $map): void
    {
        $this->map = $map;
    }

    /** @return ClassLike[] */
    public function select(): array
    {
        /** @var ComposerPackage|null $package */
        $package = $this->map->getComposerPackages()[$this->packageAlias] ?? null;

        if ($package === null) {
            $this->eventDispatcher->dispatch(
                new ErrorEvent("Package " . $this->packageAlias . "not found in configuration")
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
