<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldExtend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeFinal\ShouldBeFinal;
use PHPat\Rule\Assertion\Relation\ShouldExtend\ParentClassRule;
use PHPat\Rule\Assertion\Relation\ShouldExtend\ShouldExtend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAbstractClassTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ParentClassRule>
 */
class FinalClassTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should be final', FixtureClass::class), 28],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldBeFinal::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleAbstractClassTwo::class, false)]
        );

        return new ParentClassRule(
            new StatementBuilderFactory($testParser),
            $this->createMock(Configuration::class),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
