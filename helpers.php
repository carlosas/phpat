<?php

use PhpParser\Node\Name;

function extractNamespaceFromFQCN(string $classname): string
{
    $parts = explode('\\', $classname);
    array_pop($parts);

    return implode('\\', $parts);
}

function trimSeparators(string $name): string
{
    return rtrim(ltrim($name, '\\'), '\\');
}

function isRegularExpression(string $string)
{
    set_error_handler(function () {
    }, E_WARNING);
    $isRegularExpression = preg_match($string, "") !== false;
    restore_error_handler();

    return $isRegularExpression;
}

/**
 * @param iterable<Name> $names
 * @return iterable<class-string>
 */
function namesToClassStrings(iterable $names): iterable
{
    return array_map(
        static fn (Name $name): string => $name->toString(),
        $names
    );
}
