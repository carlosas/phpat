<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\Named\ClassnameRule;
use PHPat\Selector\Classname;
use PHPat\Selector\ClassNamespace;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassnameRule>
 * @internal
 * @coversNothing
 */
class ClassnameTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldBeNamed\ClassnameTest\Subject';

    public function testRule(): void
    {
        // Class not matching expected name — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeNamed\ClassnameTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be named SuperCoolClass', self::SUBJECT), 3],
        ]);

        // Class matching regex — no errors
        $regexSubject = 'Fixture\ShouldBeNamed\ClassnameRegexTest\SubjectClass';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeNamed\ClassnameRegexTest;
            class SubjectClass {}
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname($regexSubject, false)],
            [],
            [],
            ['isRegex' => true, 'classname' => '/.*Class$/']
        );

        $rule2 = new ClassnameRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);

        // Class in a different namespace — subject selector does not match, no errors
        $file3 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeNamed\ClassnamespaceTest\Other;
            class OtherClass {}
            PHP);

        $testParser3 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new ClassNamespace('Fixture\ShouldBeNamed\ClassnamespaceTest\Target', false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'SomeSpecificName']
        );

        $rule3 = new ClassnameRule(
            new StatementBuilder($testParser3),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file3], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname(self::SUBJECT, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'SuperCoolClass']
        );

        return new ClassnameRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
