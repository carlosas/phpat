<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Mixin;

use Tests\PHPat\fixtures\SimpleTrait;

class IncludeMultipleTraits
{
    use SimpleTrait;
    use MixinNamespaceSimpleTrait;
}