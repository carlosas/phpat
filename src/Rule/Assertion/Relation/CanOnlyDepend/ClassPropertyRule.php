<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\ClassPropertyExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\Property>
 */
final class ClassPropertyRule extends CanOnlyDepend implements Rule
{
    use ClassPropertyExtractor;
}
