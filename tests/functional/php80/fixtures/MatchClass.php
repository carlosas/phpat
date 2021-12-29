<?php

namespace Tests\PhpAT\functional\php80\fixtures;

class MatchClass
{
    public function getClassNumber($foo): string
    {
        return match ($foo) {
            $foo instanceof SimpleClassOne => 'one',
            $foo instanceof SimpleClassTwo => 'two',
            default => throw new DummyException('fail')
        };
    }
}
