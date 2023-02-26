<?php

namespace Tests\PHPat\unit;

class ErrorMessage
{
    public const SHOULD_BE_ABSTRACT = '%s should be abstract';
    public const SHOULD_NOT_BE_ABSTRACT = '%s should not be abstract';
    public const SHOULD_BE_FINAL = '%s should be final';
    public const SHOULD_NOT_BE_FINAL = '%s should not be final';

    public const SHOULD_NOT_CONSTRUCT = '%s should not construct %s';
    public const SHOULD_EXTEND = '%s should extend %s';
    public const SHOULD_NOT_EXTEND = '%s should not extend %s';
    public const SHOULD_IMPLEMENT = '%s should implement %s';
    public const SHOULD_NOT_IMPLEMENT = '%s should not implement %s';
    public const SHOULD_NOT_DEPEND = '%s should not depend on %s';
    public const SHOULD_HAVE_ATTRIBUTE = '%s should have the attribute %s';
    public const SHOULD_NOT_HAVE_ATTRIBUTE = '%s should not have the attribute %s';

}
