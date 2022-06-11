<?php

declare(strict_types=1);

namespace PHPat;

use Exception;

final class ShouldNotHappenException extends Exception
{
    public function __construct()
    {
        parent::__construct('PHPat internal error.');
    }
}
