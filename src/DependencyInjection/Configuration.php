<?php declare(strict_types=1);

namespace PHPArchiTest\DependencyInjection;

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

    public function getOriginIncluded(): array
    {
        return $this->config['files']['origin_included'] ?? [];
    }

    public function getDestinationIncluded(): array
    {
        return $this->config['files']['destination_included'] ?? [];
    }

    public function getOriginExcluded(): array
    {
        return $this->config['files']['origin_excluded'] ?? [];
    }

    public function getDestinationExcluded(): array
    {
        return $this->config['files']['destination_excluded'] ?? [];
    }

    public function getTestsPath(): string
    {
        return $this->config['tests']['path'] ?? '';
    }
}
