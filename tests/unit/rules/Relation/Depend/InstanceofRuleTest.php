<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\InstanceofRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<InstanceofRule>
 * @internal
 * @coversNothing
 */
class InstanceofRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::ShouldNot;

    public function testShouldNotConstraint(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\Instanceof\ShouldNotConstraint;
            class Target {}
            class Subject
            {
                public function method(object $o): bool
                {
                    return $o instanceof Target;
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\Instanceof\ShouldNotConstraint\Subject', 'Fixture\Relation\Depend\Instanceof\ShouldNotConstraint\Target'), 8],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\Instanceof\CanOnlyConstraint;
            class Allowed {}
            class Target {}
            class Subject
            {
                public function method(object $o): bool
                {
                    return $o instanceof Target;
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\Instanceof\CanOnlyConstraint\Subject', 'Fixture\Relation\Depend\Instanceof\CanOnlyConstraint\Target'), 9],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::ShouldNot => [
                'Fixture\Relation\Depend\Instanceof\ShouldNotConstraint\Subject',
                'Fixture\Relation\Depend\Instanceof\ShouldNotConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Depend\Instanceof\CanOnlyConstraint\Subject',
                'Fixture\Relation\Depend\Instanceof\CanOnlyConstraint\Allowed',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new InstanceofRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
