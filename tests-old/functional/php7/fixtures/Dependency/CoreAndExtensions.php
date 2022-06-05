<?php

namespace Tests\PHPat\unit\php7\fixtures\Dependency;

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
