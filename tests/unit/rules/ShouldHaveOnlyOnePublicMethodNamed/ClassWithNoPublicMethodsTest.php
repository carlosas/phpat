<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyOnePublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethodNamed\HasOnlyOnePublicMethodNamedRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodNamedRule>
 * @coversNothing
 * @internal
 */
class ClassWithNoPublicMethodsTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldHaveOnlyOnePublicMethodNamed\ClassWithNoPublicMethodsTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldHaveOnlyOnePublicMethodNamed\ClassWithNoPublicMethodsTest;
            class Subject
            {
                public function __construct() {}
                private function privateMethod(): void {}
                protected function protectedMethod(): void {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named %s', self::SUBJECT, 'targetMethod'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname(self::SUBJECT, false)],
            [],
            [],
            ['name' => 'targetMethod', 'isRegex' => false]
        );

        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
