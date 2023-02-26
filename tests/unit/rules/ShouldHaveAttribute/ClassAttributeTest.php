<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveAttribute;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldHaveAttribute\ClassAttributeRule;
use PHPat\Rule\Assertion\Relation\ShouldHaveAttribute\ShouldHaveAttribute;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleAttributeTwo;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassAttributeRule>
 */
class ClassAttributeTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should have as attribute %s', FixtureClass::class, SimpleAttributeTwo::class), 31],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldHaveAttribute::class,
            [new Classname(FixtureClass::class, false)],
            [new Classname(SimpleAttributeTwo::class, false)]
        );

        return new ClassAttributeRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
