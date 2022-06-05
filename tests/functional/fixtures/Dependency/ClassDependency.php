<?php

namespace Tests\PHPat\functional\fixtures\Dependency;

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
