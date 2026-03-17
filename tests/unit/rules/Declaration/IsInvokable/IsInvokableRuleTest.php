<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsInvokable;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsInvokable\IsInvokableRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInvokableRule>
 * @internal
 * @coversNothing
 */
class IsInvokableRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        // Class without __invoke — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsInvokable\ShouldConstraint;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be invokable', 'Fixture\Declaration\IsInvokable\ShouldConstraint\Subject'), 3],
        ]);

        // Class with __invoke — no errors
        $subject2 = 'Fixture\Declaration\IsInvokable\ShouldConstraintPass\Subject';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsInvokable\ShouldConstraintPass;
            class Subject
            {
                public function __invoke() {}
            }
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beInvokable',
            [new Classname($subject2, false)],
            []
        );

        $rule2 = new IsInvokableRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;

        // Class with __invoke — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsInvokable\ShouldNotConstraint;
            class Subject
            {
                public function __invoke() {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be invokable', 'Fixture\Declaration\IsInvokable\ShouldNotConstraint\Subject'), 3],
        ]);

        // Class without __invoke — no errors
        $subject2 = 'Fixture\Declaration\IsInvokable\ShouldNotConstraintPass\Subject';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsInvokable\ShouldNotConstraintPass;
            class Subject {}
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beInvokable',
            [new Classname($subject2, false)],
            []
        );

        $rule2 = new IsInvokableRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, true),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);
    }

    protected function getRule(): Rule
    {
        $subject = match ($this->constraint) {
            Constraint::Should => 'Fixture\Declaration\IsInvokable\ShouldConstraint\Subject',
            default => 'Fixture\Declaration\IsInvokable\ShouldNotConstraint\Subject',
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'beInvokable',
            [new Classname($subject, false)],
            []
        );

        return new IsInvokableRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
