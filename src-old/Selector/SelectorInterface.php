<?php

declare(strict_types=1);

namespace PHPatOld\Selector;

use PHPatOld\Parser\Ast\ReferenceMap;

/**
 * Interface SelectorInterface
 *
 * @package PHPat\Selector
 */
interface SelectorInterface
{
    public function getDependencies(): array;

    public function injectDependencies(array $dependencies);

    public function setReferenceMap(ReferenceMap $map): void;

    public function select(): array;

    public function getParameter(): string;
}
