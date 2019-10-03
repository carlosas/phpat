<?php

namespace PhpAT\Output;

class StdOutput implements OutputInterface
{

    private $okStrem;
    private $errStrem;
    /**
     * @var int
     */
    private $verbose;

    public function __construct($verbose = VerboseLevel::NORMAL)
    {
        $this->okStrem  = \STDOUT;
        $this->errStrem = \STDERR;
        $this->verbose  = $verbose;
    }

    public function write(string $msg, bool $error = false, int $verbose = VerboseLevel::NORMAL): void
    {
        $this->out($msg, $error, $verbose);
    }

    public function writeLn(string $msg, bool $error = false, int $verbose = VerboseLevel::NORMAL): void
    {
        $msg .= \PHP_EOL;
        $this->out($msg, $error, $verbose);
    }

    private function out(string $msg, bool $error, int $verbose): void
    {
        if ($verbose > $this->verbose) {
            return;
        }
        $stream = $error ? $this->errStrem : $this->okStrem;
        fwrite($stream, $msg);
    }
}