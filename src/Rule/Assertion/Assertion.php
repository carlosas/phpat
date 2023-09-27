<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion;

use PhpParser\Node;
use PHPStan\Rules\Rule as PHPStanRule;

/**
 * @extends PHPStanRule<Node>
 */
interface Assertion extends PHPStanRule {}
