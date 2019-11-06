<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Mixin;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleTrait;

class IncludeMultipleTraits
{
    use SimpleTrait, MixinNamespaceSimpleTrait;
}
