<?php

namespace Tests\PhpAT\benchmark;

use PhpAT\App;
use Psr\EventDispatcher\EventDispatcherInterface;

class NullDispatcher implements EventDispatcherInterface
{
    public function dispatch(object $event)
    {
        return;
    }
}

class AppExecutionBench
{
    /**
     * @Revs(1)
     * @Iterations(500)
     */
    public function benchConsume()
    {
        $app = new App();
        $reflectionClass = new \ReflectionClass($app);
        $reflectionClass->getProperty('dispatcher')->setValue(new NullDispatcher());

        $app->run();
    }
}
