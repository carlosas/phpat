<?php

namespace Tests\PHPat\functional\fixtures\Dependency;

use BadMethodCallException;
use Exception;

class CoreAndExtensions
{
    /**
     * @throws Exception
     */
    public function doSomething()
    {
        throw new BadMethodCallException();
    }
}
