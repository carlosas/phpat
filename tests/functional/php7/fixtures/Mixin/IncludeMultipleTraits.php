<?php

namespace Tests\PhpAT\functional\php7\fixtures\Mixin;

use Tests\PhpAT\functional\php7\fixtures\SimpleTrait;

class IncludeMultipleTraits
{
    use SimpleTrait, MixinNamespaceSimpleTrait;
}
