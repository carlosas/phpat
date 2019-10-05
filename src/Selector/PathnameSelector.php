<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\File\FileFinder;

/**
 * Class PathnameSelector
 * @package PhpAT\Selector
 */
class PathnameSelector implements SelectorInterface
{
    private const DEPENDENCIES = [
        FileFinder::class
    ];

    /** @var string */
    private $pathname;
    /** @var FileFinder */
    private $fileFinder;

    public function __construct(string $pathname)
    {
        $this->pathname = $pathname;
    }

    public function getDependencies(): array
    {
        return self::DEPENDENCIES;
    }

    public function injectDependencies(array $dependencies): void
    {
        $this->fileFinder = $dependencies[FileFinder::class];
    }

    public function select(): array
    {
        return $this->fileFinder->findFiles($this->pathname);
    }
}
