<?php declare(strict_types=1);

namespace PhpAT\DependencyInjection;

class Configuration
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getSrcPath(): string
    {
        return $this->config['files']['src_path'] ?? '';
    }

    public function getSrcIncluded(): array
    {
        return $this->config['files']['src_included'] ?? [];
    }

    public function getSrcExcluded(): array
    {
        return $this->config['files']['src_excluded'] ?? [];
    }

    public function getTestsPath(): string
    {
        return $this->config['tests']['path'] ?? '';
    }
}
