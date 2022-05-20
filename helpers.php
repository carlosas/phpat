<?php

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
