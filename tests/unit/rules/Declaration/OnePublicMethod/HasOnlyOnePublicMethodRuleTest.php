<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\OnePublicMethod;

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
class HasOnlyOnePublicMethodRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\Declaration\OnePublicMethod\ShouldConstraint\Subject';

    public function testShouldConstraint(): void
    {
        // Class with two public methods — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethod\ShouldConstraint;
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
        $subject2 = 'Fixture\Declaration\OnePublicMethod\ShouldConstraintPass\Subject';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\OnePublicMethod\ShouldConstraintPass;
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
