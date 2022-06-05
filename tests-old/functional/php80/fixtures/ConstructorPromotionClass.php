<?php

namespace Tests\PHPat\unit\php80\fixtures;

class ConstructorPromotionClass
{
    public function __construct(
        public string $name,
        public SimpleClassOne $simpleClass,
        public ?SimpleClassTwo $simpleClassTwo,
        public SimpleClassThree|SimpleClassFour $anotherClass,
        public \DateTimeImmutable $birthDate
    ) {}
}
