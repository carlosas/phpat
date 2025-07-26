<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors\Filepath;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Declaration\ShouldBeNamed\ClassnameRule;
use PHPat\Rule\Assertion\Declaration\ShouldBeNamed\ShouldBeNamed;
use PHPat\Selector\Filepath;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassnameRule>
 * @internal
 * @covers \PHPat\Selector\Filepath
 */
final class NoRegexTest extends RuleTestCase
{
    public const RULE_NAME = 'testSimpleClassShouldBeNamed';

    public function testExactFilename(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleClass.php'], [
            [sprintf('%s should be named SuperCoolClass', SimpleClass::class), 5],
        ]);
    }

    public function testDoesNotMatchDifferentFilename(): void
    {
        $this->analyse(['tests/fixtures/Simple/SimpleClassTwo.php'], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            ShouldBeNamed::class,
            [new Filepath('tests/fixtures/Simple/SimpleClass.php', false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'SuperCoolClass']
        );

        return new ClassnameRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
