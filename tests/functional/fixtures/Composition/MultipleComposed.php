<?php

declare(strict_types=1);

namespace Tests\PHPat\functional\fixtures\Composition;

use Tests\PHPat\functional\fixtures\SimpleInterface;

class MultipleComposed implements SimpleInterface, CompositionNamespaceSimpleInterface
{
}
