<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotExtend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotExtend\ParentClassRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAbstractClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<\PHPat\Rule\Assertion\Relation\ShouldNotExtend\ParentClassRule>
 */
class ParentClassTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not extend %s', FixtureClass::class, SimpleAbstractClass::class), 28],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            \PHPat\Rule\Assertion\Relation\ShouldNotExtend\ShouldNotExtend::class,
            [new Classname(FixtureClass::class)],
            [new Classname(SimpleAbstractClass::class)]
        );

        return new \PHPat\Rule\Assertion\Relation\ShouldNotExtend\ParentClassRule(
            new StatementBuilderFactory($testParser),
            $this->createMock(Configuration::class),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
