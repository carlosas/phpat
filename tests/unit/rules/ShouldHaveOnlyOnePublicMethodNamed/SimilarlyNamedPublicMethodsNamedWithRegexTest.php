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
class SimilarlyNamedPublicMethodsNamedWithRegexTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldHaveOnlyOnePublicMethodNamed';
    private const SUBJECT = 'Fixture\ShouldHaveOnlyOnePublicMethodNamed\SimilarlyNamedPublicMethodsNamedWithRegexTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldHaveOnlyOnePublicMethodNamed\SimilarlyNamedPublicMethodsNamedWithRegexTest;
            class Subject
            {
                public function exampleOne(): void {}
                public function exampleTwo(): void {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named %s', self::SUBJECT, '/^example[a-zA-Z0-9]+/'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname(self::SUBJECT, false)],
            [],
            [],
            ['name' => '/^example[a-zA-Z0-9]+/', 'isRegex' => true]
        );

        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            self::createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
