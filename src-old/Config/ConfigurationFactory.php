<?php

namespace PHPatOld\Config;

use PHPatOld\App\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationFactory
{
    private array $defaults = [
        'verbosity'             => 0,
        'php-version'           => null,
        'ignore-docblocks'      => false,
        'ignore-php-extensions' => true,
        'composer'              => ['main' => ['json' => 'composer.json', 'lock' => 'composer.lock']],
        'baseline-path'         => 'phpat-baseline.json',
    ];

    public function create(InputInterface $input): Configuration
    {
        $configFilePath = $input->getArgument('config');
        $commandOptions = $input->getOptions();

        $config            = Yaml::parse(file_get_contents($configFilePath));
        $config['options'] = array_merge($config['options'] ?? [], array_filter($commandOptions));

        return new Configuration(
            dirname($configFilePath),
            $config['src']['path']      ?? '',
            $config['src']['include']   ?? [],
            $config['src']['exclude']   ?? [],
            $config['composer']         ?? $this->defaults['composer'],
            $config['tests']['path']    ?? '',
            $commandOptions['baseline'] ?? $config['tests']['baseline'] ?? '',
            $this->decideVerbosity($commandOptions, $config),
            $config['options']['php-version'] ?? $this->defaults['php-version'],
            $this->decideBaselineGeneration($input),
            (bool) ($config['options']['ignore-docblocks']      ?? $this->defaults['ignore-docblocks']),
            (bool) ($config['options']['ignore-php-extensions'] ?? $this->defaults['ignore-php-extensions'])
        );
    }

    private function decideVerbosity(array $commandOptions, $config): int
    {
        if ($commandOptions['quiet'] === true) {
            return 0;
        }

        return ($config['options']['verbosity'] ?? $this->defaults['verbosity']);
    }

    private function decideBaselineGeneration(InputInterface $input): ?string
    {
        $opt = $input->getParameterOption('--generate-baseline', 'not-defined');
        if ($opt === 'not-defined') {
            return null;
        }

        return $opt ?? $this->defaults['baseline-path'];
    }
}
