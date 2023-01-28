<?php

declare(strict_types=1);

namespace Tests\PHPat\Unit\Rules\ShouldNotBeAbstract;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeAbstract\AbstractRule;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeAbstract\ShouldNotBeAbstract;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\Fixtures\Simple\SimpleAbstractClass;
use Tests\PHPat\Unit\FakeTestParser;
use Tests\PHPat\Unit\ErrorMessage;

/**
 * @extends RuleTestCase<AbstractRule>
 */
class AbstractClassTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleAbstractClass.php'], [
            [sprintf(ErrorMessage::SHOULD_NOT_BE_ABSTRACT, SimpleAbstractClass::class), 7],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotBeAbstract::class,
            [new Classname(SimpleAbstractClass::class, false)],
            []
        );

        return new AbstractRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
