<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Implement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Implement\ImplementedInterfacesRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ImplementedInterfacesRule>
 * @internal
 * @coversNothing
 */
class ImplementedInterfacesRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        // Class not implementing — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Implement\ShouldConstraint;
            interface Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should implement %s', 'Fixture\Relation\Implement\ShouldConstraint\Subject', 'Fixture\Relation\Implement\ShouldConstraint\Target'), 4],
        ]);

        // Class correctly implementing — no errors
        $subject2 = 'Fixture\Relation\Implement\ShouldConstraintPass\Subject';
        $target2 = 'Fixture\Relation\Implement\ShouldConstraintPass\Target';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Implement\ShouldConstraintPass;
            interface Target {}
            class Subject implements Target {}
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'implement',
            [new Classname($subject2, false)],
            [new Classname($target2, false)]
        );

        $rule2 = new ImplementedInterfacesRule(
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

        // Class implementing — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Implement\ShouldNotConstraint;
            interface Target {}
            class Subject implements Target {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not implement %s', 'Fixture\Relation\Implement\ShouldNotConstraint\Subject', 'Fixture\Relation\Implement\ShouldNotConstraint\Target'), 4],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::Should => [
                'Fixture\Relation\Implement\ShouldConstraint\Subject',
                'Fixture\Relation\Implement\ShouldConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Implement\ShouldNotConstraint\Subject',
                'Fixture\Relation\Implement\ShouldNotConstraint\Target',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'implement',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new ImplementedInterfacesRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
