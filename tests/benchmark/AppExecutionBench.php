<?php

namespace Tests\PhpAT\benchmark;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class AppExecutionBench
{
    public function benchRun()
    {
        (new \PhpAT\App())->run(new ArrayInput([]), new NullOutput());
    }
}
