<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\MethodReturnRule;
use PHPat\Rule\Assertion\Relation\ShouldNotDepend\ShouldNotDepend;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\unit\FakeTestParser;
use Tests\PHPat\unit\ErrorMessage;

/**
 * @extends RuleTestCase<MethodReturnRule>
 */
class MethodReturnTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleInterface::class), 48],
            [sprintf(ErrorMessage::SHOULD_NOT_DEPEND, FixtureClass::class, SimpleClass::class), 53],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotDepend::class,
            [new Classname(FixtureClass::class, false)],
            [
                new Classname(SimpleClass::class, false),
                new Classname(SimpleInterface::class, false),
            ]
        );

        return new MethodReturnRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
