<?php declare(strict_types=1);

namespace PHPat;

final class ShouldNotHappenException extends \Exception
{
    public function __construct(string $message = 'Internal error')
    {
        parent::__construct('PHPat: '.$message);
    }
}
