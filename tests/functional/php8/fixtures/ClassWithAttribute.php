<?php

namespace Tests\PhpAT\functional\php8\fixtures;

#[DummyAttributeOne]
class ClassWithAttribute
{
    #[DummyAttributeTwo]
    public function someMethod(): void
    {
    }
}
