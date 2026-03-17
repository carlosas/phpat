<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Construct;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Construct\NewRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 * @internal
 * @coversNothing
 */
class NewRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\Relation\Construct\NewRule\Subject';
    private const TARGET_1 = 'Fixture\Relation\Construct\NewRule\Target1';
    private const TARGET_2 = 'Fixture\Relation\Construct\NewRule\Target2';

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        $this->constraint = Constraint::Should;
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Construct\NewRule;
            class Target1 {}
            class Target2 {}
            class Subject
            {
                public function create(): Target2
                {
                    return new Target2();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should construct %s', self::SUBJECT, self::TARGET_1), 9],
        ]);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Construct\NewRule;
            class Target1 {}
            class Target2 {}
            class Subject
            {
                public function create(): Target1
                {
                    return new Target1();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not construct %s', self::SUBJECT, self::TARGET_1), 9],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;

        // Subject constructs Forbidden (not in allowed list) — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Construct\NewRule;
            class Target1 {}
            class Target2 {}
            class Subject
            {
                public function run(): Target1
                {
                    $a = new Target2();
                    return new Target1();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not construct %s', self::SUBJECT, self::TARGET_2), 9],
        ]);
    }

    protected function getRule(): Rule
    {
        $target = match ($this->constraint) {
            Constraint::Should => self::TARGET_1,
            Constraint::ShouldNot => self::TARGET_1,
            Constraint::CanOnly => self::TARGET_1,
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'construct',
            [new Classname(self::SUBJECT, false)],
            [new Classname($target, false)]
        );

        return new NewRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
