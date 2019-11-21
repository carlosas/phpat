<?php

declare(strict_types=1);

namespace PhpAT\App;

class Configuration
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getSrcPath(): string
    {
        return $this->config['src']['path'] ?? '';
    }

    public function getSrcIncluded(): array
    {
        return $this->config['src']['include'] ?? [];
    }

    public function getSrcExcluded(): array
    {
        return $this->config['src']['exclude'] ?? [];
    }

    public function getTestsPath(): string
    {
        return $this->config['tests']['path'] ?? '';
    }

    public function getOptVerbosity(): int
    {
        return (int) ($this->config['options']['verbosity'] ?? 1);
    }

    public function getOptDependencyCheckDocBlocks(): bool
    {
        return (bool) ($this->config['options']['dependency']['ignore_docblocks'] ?? false);
    }

    public function getDryRun(): bool
    {
        return (bool) ($this->config['options']['dry-run'] ?? false);
    }
}
