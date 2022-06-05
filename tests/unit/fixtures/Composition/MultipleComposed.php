<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Composition;

use Tests\PHPat\unit\fixtures\SimpleInterface;

class MultipleComposed implements SimpleInterface, CompositionNamespaceSimpleInterface
{
}
