<?php

namespace PhpAT\Parser\Ast;

class RegexClassName implements ClassLike
{
    private string $originalRegex;
    private string $regex;

    public function __construct(string $expression)
    {
        $this->originalRegex = $expression;
        $this->regex = str_replace(
            '*',
            '.*',
            preg_replace_callback(
                '/([^*])/',
                function ($m) {
                    return preg_quote($m[0], '/');
                },
                $expression
            )
        );
    }

    public function matches(string $name): bool
    {
        return (bool) preg_match('/^' . $this->regex . '$/i', $name);
    }

    public function toString(): string
    {
        return $this->originalRegex;
    }
}
