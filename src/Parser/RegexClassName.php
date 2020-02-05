<?php

namespace PhpAT\Parser;

class RegexClassName implements ClassLike
{
    private $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function matches(string $name): bool
    {
        return preg_match($this->regex, $name);
    }

    public function toString(): string
    {
        return $this->regex;
    }
}
