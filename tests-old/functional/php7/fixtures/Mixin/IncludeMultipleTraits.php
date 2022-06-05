<?php

namespace Tests\PHPat\unit\php7\fixtures\Mixin;

use Tests\PHPat\unit\php7\fixtures\SimpleTrait;

class IncludeMultipleTraits
{
    use SimpleTrait;
    use MixinNamespaceSimpleTrait;
}
