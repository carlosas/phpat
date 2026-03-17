<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\MethodParamRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<MethodParamRule>
 * @internal
 * @coversNothing
 */
class MethodParamRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::ShouldNot;

    public function testShouldNotConstraint(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\MethodParam\ShouldNotConstraint;
            class Target {}
            class Subject
            {
                public function method(Target $p) {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\MethodParam\ShouldNotConstraint\Subject', 'Fixture\Relation\Depend\MethodParam\ShouldNotConstraint\Target'), 6],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\MethodParam\CanOnlyConstraint;
            class Allowed {}
            class Target {}
            class Subject
            {
                public function method(Target $p) {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\MethodParam\CanOnlyConstraint\Subject', 'Fixture\Relation\Depend\MethodParam\CanOnlyConstraint\Target'), 7],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::ShouldNot => [
                'Fixture\Relation\Depend\MethodParam\ShouldNotConstraint\Subject',
                'Fixture\Relation\Depend\MethodParam\ShouldNotConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Depend\MethodParam\CanOnlyConstraint\Subject',
                'Fixture\Relation\Depend\MethodParam\CanOnlyConstraint\Allowed',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new MethodParamRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
