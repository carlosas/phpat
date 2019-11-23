<?php

namespace PhpAT\Output;

class StdOutput implements OutputInterface
{
    /**
     * @var resource
     */
    private const OK_STREAM = \STDOUT;
    /**
     * @var resource
     */
    private const ERR_STREAM = \STDERR;

    /**
     * @var int
     */
    private $verbose;

    public function __construct($verbose = VerboseLevel::VERBOSE)
    {
        $this->verbose  = $verbose;
    }

    public function write(string $message, int $level = OutputLevel::DEFAULT): void
    {
        $this->out($message, $level);
    }

    public function writeLn(string $message, int $level = OutputLevel::DEFAULT): void
    {
        $message .= PHP_EOL;
        $this->out($message, $level);
    }

    private function out(string $message, int $level): void
    {
        if (!in_array($level, VerboseLevel::OUTPUT_LEVEL[$this->verbose])) {
            return;
        }
        $stream = $level > OutputLevel::WARNING ? self::ERR_STREAM : self::OK_STREAM;
        fwrite($stream, $message);
    }
}
