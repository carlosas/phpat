<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\App\Configuration;
use PhpAT\App\Event\ErrorEvent;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\ComposerFileParser;
use PhpAT\Parser\RegexClassName;

/**
 * Class ComposerSourceSelector
 */
class ComposerSourceSelector implements SelectorInterface
{
    private const DEPENDENCIES = [
        EventDispatcher::class,
        Configuration::class,
        ComposerFileParser::class
    ];

    /** @var ReferenceMap */
    private $map;
    /** @var bool */
    private $devMode;
    /** @var EventDispatcher */
    private $eventDispatcher;
    /** @var ComposerFileParser */
    private $composer;
    /** @var Configuration */
    private $configuration;
    /** @var string */
    private $packageAlias;

    public function __construct(string $packageAlias, bool $devMode)
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
        $this->configuration = $dependencies[Configuration::class];
        $this->composer = $dependencies[ComposerFileParser::class];
    }

    /** @param ReferenceMap $map */
    public function setReferenceMap(ReferenceMap $map): void
    {
        $this->map = $map;
    }

    /** @return ClassLike[] */
    public function select(): array
    {
        try {
            $parsed = $this->composer->parse($this->configuration, $this->packageAlias);
        } catch (\Throwable $e) {
            $errorEvent = new ErrorEvent(
                'Composer package "' . $this->packageAlias . '" is not properly configured'
            );
            $this->eventDispatcher->dispatch($errorEvent);

            return [];
        }

        $namespaces = $parsed->getNamespaces($this->devMode);

        return array_map(
            function (string $namespace) {
                return new RegexClassName($namespace . '*');
            },
            $namespaces
        );
    }

    public function getParameter(): string
    {
        return sprintf('%s (%s)', $this->packageAlias, $this->devMode);
    }
}
