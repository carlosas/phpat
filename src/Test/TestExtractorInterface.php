<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPStan\BetterReflection\Reflection\Adapter\ReflectionClass;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionEnum;

interface TestExtractorInterface
{
    /**
     * @return iterable<ReflectionClass|ReflectionEnum>
     */
    public function __invoke(): iterable;
}
