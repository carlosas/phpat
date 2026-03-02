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
 * @internal
 * @coversNothing
 */
class MoreThanOnePublicMethodNamedWithRegexTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldHaveOnlyOnePublicMethodNamed\MoreThanOnePublicMethodNamedWithRegexTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldHaveOnlyOnePublicMethodNamed\MoreThanOnePublicMethodNamedWithRegexTest;
            class Subject
            {
                public function bar(): void {}
                public function foo(): void {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named %s', self::SUBJECT, '/^ba[a-zA-Z0-9]+/'), 3],
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
            ['name' => '/^ba[a-zA-Z0-9]+/', 'isRegex' => true]
        );

        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
