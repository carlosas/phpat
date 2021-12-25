<?php

namespace Tests\PhpAT\benchmark;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class AppExecutionBench
{
    /**
     * @Iterations(10)
     * @Sleep(1000)
     * @ParamProviders({"provideConfigPaths"})
     */
    public function benchRun($params)
    {
        (new \PhpAT\App())->run(new ArrayInput(['config' => realpath($params['path'])]), new NullOutput());
    }

    public function provideConfigPaths(): array
    {
        return [
            'base' => ['path' => __DIR__ . '/../../ci/phpat.yaml'],
            'functional7' => ['path' => __DIR__ . '/../../tests/functional/functional7.yaml'],
            'functional8' => ['path' => __DIR__ . '/../../tests/functional/functional8.yaml'],
        ];
    }
}
