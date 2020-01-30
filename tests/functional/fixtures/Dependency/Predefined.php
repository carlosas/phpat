<?php

namespace Tests\PhpAT\functional\fixtures\Dependency;

class Predefined
{
    /**
     * @throws \Exception
     */
    public function doSomething()
    {
        throw new \BadMethodCallException();
    }
}
