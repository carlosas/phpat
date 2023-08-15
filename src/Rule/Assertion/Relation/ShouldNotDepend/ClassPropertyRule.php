<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\ClassPropertyExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\Property>
 */
class ClassPropertyRule extends ShouldNotDepend implements Rule
{
    use ClassPropertyExtractor;
}
