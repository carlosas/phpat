<?php

namespace PhpAT\Rule;

use PhpAT\App\Configuration;
use PhpAT\Rule\Event\BaselineObsoleteEvent;
use PHPAT\EventDispatcher\EventDispatcher;

final class Baseline
{
    private array $baselineErrors;
    private EventDispatcher $eventDispatcher;

    public function __construct(Configuration $configuration, EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $path = $configuration->getBaselineFilePath();
        $this->baselineErrors = is_file($path)
            ? json_decode(file_get_contents($path), true)
            : [];
    }

    public function compensateError(string $ruleName, string $error): bool
    {
        if (!isset($this->baselineErrors[$ruleName])) {
            return false;
        }

        $error = array_search($error, $this->baselineErrors[$ruleName]);
        if ($error === false) {
            return false;
        }

        unset($this->baselineErrors[$ruleName][$error]);
        return true;
    }

    public function checkNonCompensatedErrors(): void
    {
        if (count(array_filter($this->baselineErrors)) !== 0) {
            $this->eventDispatcher->dispatch(
                new BaselineObsoleteEvent('Baseline file references non found errors, please regenerate it!')
            );
        }
    }
}
