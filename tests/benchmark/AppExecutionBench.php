<?php

namespace Tests\PhpAT\benchmark;

use PhpAT\App;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class AppExecutionBench
{
    /**
     * @Iterations(10)
     * @Sleep(1000)
     */
    public function benchRunBase()
    {
        $path = realpath(__DIR__ . '/../../ci/phpat.yaml');
        (new App())->run(new ArrayInput(['config' => $path]), new NullOutput());
    }

    /**
     * @Iterations(10)
     * @Sleep(1000)
     */
    public function benchRunFunctional7()
    {
        $path = realpath(__DIR__ . '/../../tests/functional/functional7.yaml');
        (new App())->run(new ArrayInput(['config' => $path]), new NullOutput());
    }

    /**
     * @Iterations(10)
     * @Sleep(1000)
     */
    public function benchRunFunctional8()
    {
        $path = realpath(__DIR__ . '/../../tests/functional/functional8.yaml');
        (new App())->run(new ArrayInput(['config' => $path]), new NullOutput());
    }
}
