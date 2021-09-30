<?php

namespace PhpAT\Parser\Ast\Locator;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;

class SourceLocator
{
    /** @var FileFinder */
    private $finder;
    /** @var Configuration */
    private $configuration;

    public function __construct(
        FileFinder $finder,
        Configuration $configuration
    ) {
        $this->finder = $finder;
        $this->configuration = $configuration;
    }

    public function locateSrcFiles()
    {
        $composerConfiguration = $this->configuration->getComposerConfiguration();
    }
}
