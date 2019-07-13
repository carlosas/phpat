<?php
declare(strict_types=1);

namespace PHPArchiTest\Rule;

use Roave\BetterReflection\Reflection\ReflectionClass;

class Inheritance implements RuleType
{
    public function satisfies(ReflectionClass $origin, ReflectionClass $destination): bool
    {
        try {
            $parents = $origin->getParentClassNames();
        } catch (\Exception $e) {
            echo $e->getMessage(); die;
        }

        if (empty($parents) || !in_array($destination->getName(), $parents)) {
            return false;
        };

        return true;
    }

    public function getMessageVerb(): string
    {
        return 'extend';
    }
}
