<?php declare(strict_types=1);

namespace PhpAT\Selector;

interface SelectorInterface
{
    /** @return string[] */
    public function getDependencies(): array;

    public function injectDependencies(array $dependencies);

    /** @return \SplFileInfo[] */
    public function select(): array;
}
