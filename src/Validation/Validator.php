<?php declare(strict_types=1);

namespace PhpAT\Validation;

use PhpAT\Statement\Statement;
use PhpAT\Statement\StatementNotValidException;

class Validator
{
    /**
     * @param Statement $statement
     * @throws StatementNotValidException
     */
    public function validate(Statement $statement): void
    {
        if (false === (
                $statement->isInverse()
                xor $statement->getType()->validate($statement->getOrigin(), $statement->getParams())
            )
        ) {
            throw new StatementNotValidException($statement->getErrorMessage());
        }
    }
}
