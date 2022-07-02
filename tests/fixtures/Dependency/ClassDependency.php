<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Dependency;

class ClassDependency extends \Tests\PHPat\unit\fixtures\Dependency\ParentClass implements \Tests\PHPat\unit\fixtures\Dependency\ClassInterface
{
    use \Tests\PHPat\unit\fixtures\Dependency\ClassTrait;
}

abstract class ParentClass
{
}

interface ClassInterface
{
}

trait ClassTrait
{
}
