<?php

declare(strict_types=1);

namespace PhpAT\App;

class RuleValidationStorage
{
    /**
     * @var array
     */
    private $errors = [];
    /**
     * @var array
     */
    private $fatalErrors = [];
    /**
     * @var bool
     */
    private $anyRuleHadErrors = false;
    /**
     * @var bool
     */
    private $lastRuleHadErrors = false;

    /**
     * @param string $message
     */
    public function addError(string $message): void
    {
        $this->errors[] = $message;
        $this->lastRuleHadErrors = true;
        $this->anyRuleHadErrors = true;
    }

    /**
     * @param string $message
     */
    public function addFatalError(string $message): void
    {
        $this->fatalErrors[] = $message;
        $this->anyRuleHadErrors = true;
    }

    /**
     * @return array
     */
    public function flushErrors(): array
    {
        $e = $this->errors;
        $this->errors = [];
        $this->lastRuleHadErrors = false;

        return $e;
    }

    /**
     * @return bool
     */
    public function anyRuleHadErrors(): bool
    {
        return $this->anyRuleHadErrors;
    }

    /**
     * @return bool
     */
    public function lastRuleHadErrors(): bool
    {
        return $this->lastRuleHadErrors;
    }
}
