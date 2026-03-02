<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\ConstantUseRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ConstantUseRule>
 * @internal
 * @coversNothing
 */
class ConstantUseRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::ShouldNot;

    public function testShouldNotConstraint(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\ConstantUse\ShouldNotConstraint;
            class Target
            {
                public const CONSTANT = 'value';
            }
            class Subject
            {
                public function method(): string
                {
                    return Target::CONSTANT;
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\ConstantUse\ShouldNotConstraint\Subject', 'Fixture\Relation\Depend\ConstantUse\ShouldNotConstraint\Target'), 11],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint;
            class Allowed
            {
                public const ALLOWED_CONSTANT = 'ok';
            }
            class Target
            {
                public const CONSTANT = 'value';
            }
            class Subject
            {
                public function method(): string
                {
                    return Target::CONSTANT;
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint\Subject', 'Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint\Target'), 15],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::ShouldNot => [
                'Fixture\Relation\Depend\ConstantUse\ShouldNotConstraint\Subject',
                'Fixture\Relation\Depend\ConstantUse\ShouldNotConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint\Subject',
                'Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint\Allowed',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new ConstantUseRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
