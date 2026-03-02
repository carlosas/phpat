<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyOnePublicMethod;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethod\HasOnlyOnePublicMethodRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodRule>
 * @internal
 * @coversNothing
 */
class ClassWithOnlyOnePublicMethodTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldHaveOnlyOnePublicMethod\ClassWithOnlyOnePublicMethodTest\Subject';

    public function testRule(): void
    {
        // Class with two public methods — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldHaveOnlyOnePublicMethod\ClassWithOnlyOnePublicMethodTest;
            class Subject
            {
                public function methodOne(): void {}
                public function methodTwo(): void {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method', self::SUBJECT), 3],
        ]);

        // Class with one public and one private method — no errors
        $subject2 = 'Fixture\ShouldHaveOnlyOnePublicMethod\GoodImplementationClassWithOnlyOnePublicMethodTest\Subject';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldHaveOnlyOnePublicMethod\GoodImplementationClassWithOnlyOnePublicMethodTest;
            class Subject
            {
                public function doSomething(): void {}
                private function helper(): void {}
            }
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname($subject2, false)],
            []
        );

        $rule2 = new HasOnlyOnePublicMethodRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname(self::SUBJECT, false)],
            []
        );

        return new HasOnlyOnePublicMethodRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
