<?php

namespace Tests\PhpAT\functional\php8\fixtures;

#[\Attribute(\Attribute::TARGET_METHOD)] class DummyAttributeTwo
{
    public const SOME_INTEGER = 42;
}
