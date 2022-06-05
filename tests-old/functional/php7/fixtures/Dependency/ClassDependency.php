<?php

namespace Tests\PHPat\unit\php7\fixtures\Dependency;

class ClassDependency extends ParentClass implements ClassInterface
{
    use ClassTrait;
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
