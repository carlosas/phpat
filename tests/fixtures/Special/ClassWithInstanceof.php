<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleException;
use Tests\PHPat\fixtures\Simple\SimpleInterface;

class ClassWithInstanceof
{
    public function checkWithDirectName($object): bool
    {
        return $object instanceof SimpleClass;
    }

    public function checkWithFullyQualifiedName($object): bool
    {
        return $object instanceof \Tests\PHPat\fixtures\Simple\SimpleException;
    }

    public function checkInConditional($object): void
    {
        if ($object instanceof SimpleException) {
            // Handle exception
        }
    }

    public function checkInMethodParameter($object): void
    {
        if ($object instanceof SimpleClass && is_object($object)) {
            // Handle SimpleClass
        }
    }

    public function checkMultipleInstanceof($object): void
    {
        if ($object instanceof SimpleClass || $object instanceof SimpleException) {
            // Handle either type
        }
    }

    public function checkNestedInstanceof($object): void
    {
        if ($object instanceof SimpleClass) {
            if ($object instanceof SimpleInterface) {
                // Nested check
            }
        }
    }
}