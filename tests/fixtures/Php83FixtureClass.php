<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures;

if (PHP_VERSION_ID >= 80300) { // PHP 8.3.0 or higher
    eval('class Php83FixtureClass
    {
        private const ?\Tests\PHPat\fixtures\Simple\SimpleClass CONSTANT = null;
    }');
} else { // Previous PHP versions
    class Php83FixtureClass
    {
        private const CONSTANT = null;
    }
}
