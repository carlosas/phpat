<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\ShouldNotHappenException;

interface DependencyResolverInterface
{
    /**
     * Resolves a constructor parameter dependency from the configured container.
     *
     * @param  \ReflectionParameter     $parameter The parameter to resolve
     * @return mixed                    The resolved dependency value
     * @throws ShouldNotHappenException When the dependency cannot be resolved
     */
    public function resolve(\ReflectionParameter $parameter): mixed;

    /**
     * Checks if a constructor parameter can be resolved from the configured container.
     *
     * @param  \ReflectionParameter $parameter The parameter to check
     * @return bool                 True if the parameter can be resolved, false otherwise
     */
    public function canResolve(\ReflectionParameter $parameter): bool;
}
