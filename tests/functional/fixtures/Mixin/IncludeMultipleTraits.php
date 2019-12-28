<?php

namespace Tests\PhpAT\functional\fixtures\Mixin;

use Tests\PhpAT\functional\fixtures\SimpleTrait;

class IncludeMultipleTraits
{
    use SimpleTrait, MixinNamespaceSimpleTrait;
}
