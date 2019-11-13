<?php declare(strict_types=1);

namespace PhpAT\Output;

interface OutputInterface
{
    public function write(string $msg, int $level = OutputLevel::DEFAULT): void;

    public function writeLn(string $msg, int $level = OutputLevel::DEFAULT): void;
}
