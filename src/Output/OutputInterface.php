<?php

namespace PhpAT\Output;

interface OutputInterface
{
    public function write(string $msg, bool $error = false, int $verbose = VerboseLevel::NORMAL): void;

    public function writeLn(string $msg, bool $error = false, int $verbose = VerboseLevel::NORMAL): void;
}
