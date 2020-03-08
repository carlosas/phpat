<?php

declare(strict_types=1);

namespace PhpAT\Selector;

class Selector
{
    public static function havePath(string $path): PathSelector
    {
        return new PathSelector($path);
    }

    public static function haveClassName(string $fqcn): ClassNameSelector
    {
        return new ClassNameSelector($fqcn);
    }

    public static function implementInterface(string $fqcn): ImplementSelector
    {
        return new ImplementSelector($fqcn);
    }

    public static function extendClass(string $fqcn): ExtendSelector
    {
        return new ExtendSelector($fqcn);
    }

    public static function includeTrait(string $fqcn): IncludeSelector
    {
        return new IncludeSelector($fqcn);
    }

    public static function areComposerDependencies(
        string $composerJson,
        string $composerLock,
        bool $includeDev = false
    ): ComposerDependencySelector {
        return new ComposerDependencySelector($composerJson, $composerLock, $includeDev);
    }

    public static function areComposerSource(string $composerJson, bool $includeDev = false): ComposerSourceSelector
    {
        return new ComposerSourceSelector($composerJson, $includeDev);
    }
}
