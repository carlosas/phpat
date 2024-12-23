<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Simple;

#[\Attribute(\Attribute::TARGET_ALL)]
class SimpleAttribute {
    public function __construct(public ?string $something = null) {
    }
}
