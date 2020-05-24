<?php

namespace PhpAT\Output;

interface OutputInterface
{
    public function suiteStart(): void;

    public function suiteEnd(float $time, bool $success): void;

    public function ruleValidationStart(string $ruleName): void;

    public function ruleValidationEnd(array $errorMessages, array $warningMessages): void;

    public function statementValid(string $message): void;

    public function statementNotValid(string $message): void;

    public function warning(string $message): void;

    public function error(string $message): void;

    public function fatalError(string $message): void;
}
