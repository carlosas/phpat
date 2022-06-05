<?php

declare(strict_types=1);

namespace Tests\PHPat\functional\fixtures\Mixin;

use Tests\PHPat\functional\fixtures\SimpleTrait;

class IncludeMultipleTraits
{
    use SimpleTrait;
    use MixinNamespaceSimpleTrait;
}
