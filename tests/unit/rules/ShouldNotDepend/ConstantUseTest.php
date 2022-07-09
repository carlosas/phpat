<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Rule\Assertion\ShouldNotDepend\ConstantUseRule;
use PHPat\Rule\Assertion\ShouldNotDepend\ShouldNotDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Special\ClassWithConstant;
use Tests\PHPat\fixtures\SuperClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ConstantUseRule>
 */
class ConstantUseTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/SuperClass.php'], [
            [sprintf('%s should not depend on %s', SuperClass::class, ClassWithConstant::class), 36],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotDepend::class,
            [new Classname(SuperClass::class)],
            [new Classname(ClassWithConstant::class)]
        );

        $fileTypeMapper = $this->createMock(FileTypeMapper::class);
        $fileTypeMapper->method('getResolvedPhpDoc')->willReturn(ResolvedPhpDocBlock::createEmpty());

        return new ConstantUseRule(
            new StatementBuilderFactory($testParser),
            $this->createReflectionProvider(),
            $fileTypeMapper
        );
    }
}
