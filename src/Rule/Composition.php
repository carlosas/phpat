<?php
declare(strict_types=1);

namespace PHPArchiTest\Rule;

use Roave\BetterReflection\Reflection\ReflectionClass;

class Composition implements RuleType
{
    public function satisfies(ReflectionClass $origin, ReflectionClass $destination): bool
    {
        $originInterfaces = $origin->getInterfaceNames();

        if (!in_array($destination->getName(), $originInterfaces)) {
            return false;
        }

        return true;
    }

    public function getMessageVerb(): string
    {
        return 'implement';
    }
}
