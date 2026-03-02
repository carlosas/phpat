<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\Named;

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
class ClassnameRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private string $methodName = 'SuperCoolClass';

    private bool $isRegex = false;

    public function testRule(): void
    {
        // Class not matching expected exact name — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\Named\ExactMatchTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be named SuperCoolClass', 'Fixture\Declaration\Named\ExactMatchTest\Subject'), 3],
        ]);

        // Class matching regex — no errors
        $regexSubject = 'Fixture\Declaration\Named\RegexMatchTest\SubjectClass';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\Named\RegexMatchTest;
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
            namespace Fixture\Declaration\Named\NamespaceNoMatchTest\Other;
            class OtherClass {}
            PHP);

        $testParser3 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new ClassNamespace('Fixture\Declaration\Named\NamespaceNoMatchTest\Target', false)],
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
            [new Classname('Fixture\Declaration\Named\ExactMatchTest\Subject', false)],
            [],
            [],
            ['isRegex' => $this->isRegex, 'classname' => $this->methodName]
        );

        return new ClassnameRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
