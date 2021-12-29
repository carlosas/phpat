<?php

namespace PhpAT\Rule;

use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Rule\Event\BaselineObsoleteEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

final class Baseline
{
    private array $baselineErrors;
    private ?string $generateBaselinePath;
    private EventDispatcherInterface $eventDispatcher;
    private array $storedErrors = [];

    public function __construct(Configuration $configuration, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher      = $eventDispatcher;
        $path                       = $configuration->getBaselineFilePath();
        $this->generateBaselinePath = $configuration->getGenerateBaseline();
        $this->baselineErrors       = is_file($path)
            ? json_decode(file_get_contents($path), true)
            : [];
    }

    public function compensateError(string $ruleName, string $error): bool
    {
        if ($this->generateBaselinePath !== null || !isset($this->baselineErrors[$ruleName])) {
            return false;
        }

        $error = array_search($error, $this->baselineErrors[$ruleName], true);
        if ($error === false) {
            return false;
        }

        unset($this->baselineErrors[$ruleName][$error]);
        return true;
    }

    public function checkNonCompensatedErrors(): void
    {
        if ($this->generateBaselinePath === null && count(array_filter($this->baselineErrors)) !== 0) {
            $this->eventDispatcher->dispatch(
                new BaselineObsoleteEvent('Baseline file references non found errors, please regenerate it!')
            );
        }
    }

    public function storeError(string $ruleName, string $error): void
    {
        $this->storedErrors[$ruleName][] = $error;
    }

    public function generateBaselineFileIfNeeded(): bool
    {
        if ($this->generateBaselinePath === null) {
            return false;
        }

        $file = fopen($this->generateBaselinePath, 'w');
        fwrite($file, json_encode($this->storedErrors, JSON_PRETTY_PRINT));
        fclose($file);

        return true;
    }
}
