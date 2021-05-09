<?php

namespace PhpAT\Config;

use PhpAT\App\Configuration;
use Symfony\Component\Yaml\Yaml;

class ConfigurationFactory
{
    private const DEFAULT_OPTIONS = [
        'dry-run' => false,
        'verbosity' => 0,
        'ignore-docblocks' => false,
        'ignore_php_extensions' => true
    ];

    public function create(string $configFilePath, array $commandOptions): Configuration
    {
        $config = Yaml::parse(file_get_contents($configFilePath));
        $config['options'] = array_merge($config['options'] ?? [], $commandOptions);

        return new Configuration(
            $config['src']['path'] ?? '',
            $config['src']['include'] ?? [],
            $config['src']['exclude'] ?? [],
            $config['composer'] ?? [],
            $config['tests']['path'] ?? '',
            $this->decideVerbosity($commandOptions, $config),
            (bool) ($config['options']['dry-run'] ?? static::DEFAULT_OPTIONS['dry-run']),
            (bool) ($config['options']['ignore-docblocks'] ?? static::DEFAULT_OPTIONS['ignore-docblocks']),
            (bool) ($config['options']['ignore_php_extensions'] ?? static::DEFAULT_OPTIONS['ignore_php_extensions'])
        );
    }

    private function decideVerbosity(array $commandOptions, $config): int
    {
        if ($commandOptions['quiet'] === true) {
            return 0;
        }

        return ($config['options']['verbosity'] ?? static::DEFAULT_OPTIONS['verbosity']);
    }
}
