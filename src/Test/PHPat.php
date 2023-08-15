<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Test\Builder\SubjectStep;

final class PHPat
{
    public static function rule(): SubjectStep
    {
        return new SubjectStep(new RelationRule());
    }
}
