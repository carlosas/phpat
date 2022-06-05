<?php

declare(strict_types=1);

namespace PHPatOld\Selector;

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

    public static function areAutoloadableFromComposer(
        string $composerAlias = 'main'
    ): ComposerSourceSelector {
        return new ComposerSourceSelector($composerAlias, false);
    }

    public static function areDevAutoloadableFromComposer(
        string $composerAlias = 'main'
    ): ComposerSourceSelector {
        return new ComposerSourceSelector($composerAlias, true);
    }

    public static function areDependenciesFromComposer(
        string $composerAlias = 'main'
    ): ComposerDependencySelector {
        return new ComposerDependencySelector($composerAlias, false);
    }

    public static function areDevDependenciesFromComposer(
        string $composerAlias = 'main'
    ): ComposerDependencySelector {
        return new ComposerDependencySelector($composerAlias, true);
    }
}
