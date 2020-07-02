<?php

namespace PhpAT\Parser\Ast;

class RegexClassName implements ClassLike
{
    private $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function matches(string $name): bool
    {
        $pattern = preg_replace_callback(
            '/([^*])/',
            function ($m) {
                return preg_quote($m[0], '/');
            },
            $this->regex
        );
        $pattern = str_replace('*', '.*', $pattern);

        return (bool) preg_match('/^' . $pattern . '$/i', $name);
    }

    public function toString(): string
    {
        return $this->regex;
    }
}
