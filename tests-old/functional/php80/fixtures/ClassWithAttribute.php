<?php

namespace Tests\PHPat\unit\php80\fixtures;

#[DummyAttributeOne]
class ClassWithAttribute
{
    #[DummyAttributeTwo, DummyAttributeThree('answer', DummyAttributeTwo::SOME_INTEGER)]
    public function someMethod(): void
    {
    }
}
