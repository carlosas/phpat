<?php

namespace PhpAT\Output;

class StdOutput implements OutputInterface
{
    /**
     * @var resource
     */
    private $okStream = \STDOUT;
    /**
     * @var resource
     */
    private $errStream = \STDERR;

    /**
     * @var int
     */
    private $verbose;

    public function __construct(int $verbosity, bool $dryRun)
    {
        $this->verbose = $verbosity;
        $this->errStream = $dryRun ? \STDOUT : \STDERR;
    }

    public function suiteStart(): void
    {
        $this->write(PHP_EOL, OutputLevel::DEFAULT);
        $this->writeLn('---/-------\------|-----\---/--', OutputLevel::DEFAULT);
        $this->writeLn('--/-PHP Architecture Tester/---', OutputLevel::DEFAULT);
        $this->writeLn('-/-----------\----|-------X----', OutputLevel::DEFAULT);
        $this->write(PHP_EOL, OutputLevel::DEFAULT);
    }

    public function suiteEnd(float $time, bool $success): void
    {
        $message = $success ? 'TESTS PASSED' : 'ERRORS FOUND';
        $timeMsg = round($time, 2) . 's';
        $this->writeLn(PHP_EOL . 'phpat | ' . $timeMsg . ' | ' . $message, OutputLevel::DEFAULT);
    }

    public function ruleValidationStart(string $ruleName): void
    {
        $this->write(PHP_EOL, OutputLevel::INFO);
        $this->writeLn(str_repeat('-', strlen($ruleName) + 4), OutputLevel::INFO);
        $this->writeLn('| ' . $ruleName . ' |', OutputLevel::INFO);
        $this->writeLn(str_repeat('-', strlen($ruleName) + 4), OutputLevel::INFO);
    }

    public function ruleValidationEnd(array $errorMessages, array $warningMessages): void
    {
        $this->writeLn('', OutputLevel::INFO);
        foreach ($warningMessages as $warning) {
            $this->writeLn('WARNING: ' . $warning, OutputLevel::WARNING);
        }

        if (empty($errorMessages)) {
            $this->writeLn('OK', OutputLevel::INFO);
        }

        foreach ($errorMessages as $error) {
            $this->writeLn('ERROR: ' . $error, OutputLevel::ERROR);
        }
    }

    public function statementValid(string $message): void
    {
        $this->write('.', OutputLevel::INFO);
        $this->writeLn(' ' . $message, OutputLevel::DEBUG);
    }

    public function statementNotValid(string $message): void
    {
        $this->write('X', OutputLevel::INFO);
        $this->writeLn(' ' . $message, OutputLevel::DEBUG);
    }

    public function warning(string $message): void
    {
        $this->writeLn('WARNING: ' . $message, OutputLevel::WARNING);
    }

    public function error(string $message): void
    {
        $this->writeLn('ERROR: ' . $message, OutputLevel::ERROR);
    }

    public function fatalError(string $message): void
    {
        $this->writeLn('FATAL ERROR: ' . $message, OutputLevel::ERROR);
    }

    private function write(string $message, int $level = OutputLevel::DEFAULT): void
    {
        $this->out($message, $level);
    }

    private function writeLn(string $message, int $level = OutputLevel::DEFAULT): void
    {
        $message .= PHP_EOL;
        $this->out($message, $level);
    }

    private function out(string $message, int $level): void
    {
        if (!in_array($level, VerboseLevel::OUTPUT_LEVEL[$this->verbose])) {
            return;
        }
        $stream = $level > OutputLevel::WARNING ? $this->errStream : $this->okStream;
        fwrite($stream, $message);
    }
}
