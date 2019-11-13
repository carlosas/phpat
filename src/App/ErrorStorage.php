<?php declare(strict_types=1);

namespace PhpAT\App;

class ErrorStorage
{
    /** @var string[] */
    private $errors = [];

    /** @var bool */
    private $anyRuleHadErrors = false;

    /** @var bool */
    private $lastRuleHadErrors = false;

    public function addError(string $message): void
    {
        $this->errors[] = $message;
        $this->lastRuleHadErrors = true;
        $this->anyRuleHadErrors = true;
    }

    public function flushErrors(): array
    {
        $e = $this->errors;
        $this->errors = [];
        $this->lastRuleHadErrors = false;

        return $e;
    }

    public function anyRuleHadErrors(): bool
    {
        return $this->anyRuleHadErrors;
    }

    public function lastRuleHadErrors(): bool
    {
        return $this->lastRuleHadErrors;
    }
}
