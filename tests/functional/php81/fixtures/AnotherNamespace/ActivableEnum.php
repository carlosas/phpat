<?php

namespace Tests\PhpAT\functional\php81\fixtures\AnotherNamespace;

enum ActivableEnum implements ActivableInterface
{
    case Option1;
    case Option2;
    case Option3;

    public function isActive(): bool
    {
        return true;
    }
}
