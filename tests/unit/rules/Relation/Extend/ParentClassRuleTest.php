<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Extend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Extend\ParentClassRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ParentClassRule>
 * @internal
 * @coversNothing
 */
class ParentClassRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        // Class not extending — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Extend\ShouldConstraint;
            class Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should extend %s', 'Fixture\Relation\Extend\ShouldConstraint\Subject', 'Fixture\Relation\Extend\ShouldConstraint\Target'), 4],
        ]);

        // Class correctly extending — no errors
        $subject2 = 'Fixture\Relation\Extend\ShouldConstraintPass\Subject';
        $target2 = 'Fixture\Relation\Extend\ShouldConstraintPass\Target';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Extend\ShouldConstraintPass;
            class Target {}
            class Subject extends Target {}
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'extend',
            [new Classname($subject2, false)],
            [new Classname($target2, false)]
        );

        $rule2 = new ParentClassRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;

        // Class extending — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Extend\ShouldNotConstraint;
            class Target {}
            class Subject extends Target {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not extend %s', 'Fixture\Relation\Extend\ShouldNotConstraint\Subject', 'Fixture\Relation\Extend\ShouldNotConstraint\Target'), 4],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::Should => [
                'Fixture\Relation\Extend\ShouldConstraint\Subject',
                'Fixture\Relation\Extend\ShouldConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Extend\ShouldNotConstraint\Subject',
                'Fixture\Relation\Extend\ShouldNotConstraint\Target',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'extend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new ParentClassRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
