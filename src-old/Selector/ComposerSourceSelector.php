<?php

declare(strict_types=1);

namespace PHPatOld\Selector;

use PHPatOld\Parser\Ast\ClassLike;
use PHPatOld\Parser\Ast\FullClassName;
use PHPatOld\Parser\Ast\ReferenceMap;
use PHPatOld\Rule\Event\BaselineObsoleteEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class ComposerSourceSelector
 */
class ComposerSourceSelector implements Selector
{
    private const DEPENDENCIES = [
        EventDispatcherInterface::class
    ];

    private ?ReferenceMap $map = null;
    private bool $devMode;
    private EventDispatcherInterface $eventDispatcher;
    private string $packageAlias;

    public function __construct(string $packageAlias, bool $devMode)
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

        if (!$package instanceof \PHPat\Parser\Ast\ComposerPackage) {
            $this->eventDispatcher->dispatch(
                new BaselineObsoleteEvent("Package " . $this->packageAlias . "not found in configuration")
            );

            return [];
        }

        $regexs               = $this->devMode ? $package->getDevAutoload() : $package->getAutoload();
        $regexsWithSrcClasses = [];
        foreach ($this->map->getSrcNodes() as $srcNode) {
            foreach ($regexs as $regex) {
                if ($this->matchesPattern($srcNode->getClassName(), $regex->toString())) {
                    $result[]                                 = FullClassName::createFromFQCN($srcNode->getClassName());
                    $regexsWithSrcClasses[$regex->toString()] = $regex;
                }
            }
        }

        foreach ($regexs as $regex) {
            if (!isset($regexsWithSrcClasses[$regex->toString()])) {
                $result[] = $regex;
            }
        }

        return $result ?? [];
    }

    public function getParameter(): string
    {
        return sprintf('%s (%s)', $this->packageAlias, $this->devMode ? 'true' : 'false');
    }

    protected function matchesPattern(string $className, string $pattern): bool
    {
        $pattern = preg_replace_callback(
            '/([^*])/',
            function ($m) {
                return preg_quote($m[0], '/');
            },
            $pattern
        );
        $pattern = str_replace('*', '.*', $pattern);

        return (bool) preg_match('/^' . $pattern . '$/i', $className);
    }
}
