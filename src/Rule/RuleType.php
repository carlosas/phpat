<?php
declare(strict_types=1);

namespace PHPArchiTest\Rule;

use Roave\BetterReflection\Reflection\ReflectionClass;

interface RuleType
{
    public function satisfies(ReflectionClass $origin, ReflectionClass $destination): bool;

    public function getMessageVerb(): string;
}
