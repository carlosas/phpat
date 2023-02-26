<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotBeFinal;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\IsFinalRule;
use PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal\ShouldNotBeFinal;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleFinalClass;
use Tests\PHPat\unit\FakeTestParser;
use Tests\PHPat\unit\ErrorMessage;

/**
 * @extends RuleTestCase<IsFinalRule>
 */
class FinalClassTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleFinalClass.php'], [
            [sprintf(ErrorMessage::SHOULD_NOT_BE_FINAL, SimpleFinalClass::class), 7],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            ShouldNotBeFinal::class,
            [new Classname(SimpleFinalClass::class, false)],
            []
        );

        return new IsFinalRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
