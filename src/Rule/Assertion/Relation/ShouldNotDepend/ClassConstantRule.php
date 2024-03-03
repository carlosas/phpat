<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\ClassConstantExtractor;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<ClassConst>
 */
final class ClassConstantRule extends ShouldNotDepend implements Rule
{
    use ClassConstantExtractor;
}
