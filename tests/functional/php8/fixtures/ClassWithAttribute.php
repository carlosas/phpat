<?php

namespace Tests\PhpAT\functional\php8\fixtures;

#[DummyAttributeOne]
class ClassWithAttribute
{
    #[DummyAttributeTwo, DummyAttributeThree('answer', 42)]
    public function someMethod(): void
    {
    }
}
