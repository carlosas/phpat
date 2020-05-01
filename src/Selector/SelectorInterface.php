<?php

declare(strict_types=1);

namespace PhpAT\Selector;

use PhpAT\Parser\Ast\ReferenceMap;

/**
 * Interface SelectorInterface
 *
 * @package PhpAT\Selector
 */
interface SelectorInterface
{
    public function getDependencies(): array;

    public function injectDependencies(array $dependencies);

    public function setReferenceMap(ReferenceMap $map): void;

    public function select(): array;

    public function getParameter(): string;
}
