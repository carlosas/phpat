<?php

namespace Tests\PhpAT\functional\php8\fixtures;

#[DummyAttributeOne]
class ClassWithAttribute
{
    #[DummyAttributeTwo, DummyAttributeThree('answer', DummyAttributeTwo::SOME_INTEGER)]
    public function someMethod(): void
    {
    }
}
