<?php
declare(strict_types=1);

namespace PHPArchiTest\Validation;

use PHPArchiTest\Statement\Statement;

class Validator
{
    public function validate(Statement $statement): bool
    {
        return $statement->isInverse() xor $statement->getType()->satisfies($statement->getOrigin(), $statement->getDestination());
    }
}
