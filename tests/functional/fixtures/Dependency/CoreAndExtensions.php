<?php

namespace Tests\PhpAT\functional\fixtures\Dependency;

class CoreAndExtensions
{
    /**
     * @throws \Exception
     */
    public function doSomething()
    {
        throw new \BadMethodCallException();
    }
}
