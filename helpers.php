<?php

use PhpParser\Node\Name;

function extractNamespaceFromFQCN(string $classname): string
{
    $parts = explode('\\', $classname);
    array_pop($parts);

    return removePrefixAndSuffixSeparators(implode('\\', $parts));
}

function removePrefixAndSuffixSeparators(string $name): string
{
    return rtrim(ltrim($name, '\\'), '\\');
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
