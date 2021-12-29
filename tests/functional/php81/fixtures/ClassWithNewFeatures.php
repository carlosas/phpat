<?php

namespace Tests\PhpAT\functional\php81\fixtures;

class ClassWithNewFeatures
{
    public function __construct(
        public readonly EnumClassOne $status
    ) {}
}
