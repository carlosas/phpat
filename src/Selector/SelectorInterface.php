<?php declare(strict_types=1);

namespace PhpAT\Selector;

/**
 * Interface SelectorInterface
 *
 * @package PhpAT\Selector
 */
interface SelectorInterface
{
    public function getDependencies(): array;

    public function injectDependencies(array $dependencies);

    public function select(): array;
}
