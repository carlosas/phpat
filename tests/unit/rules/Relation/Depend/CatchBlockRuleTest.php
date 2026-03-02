<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\CatchBlockRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<CatchBlockRule>
 * @internal
 * @coversNothing
 */
class CatchBlockRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::ShouldNot;

    public function testShouldNotConstraint(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\CatchBlock\ShouldNotConstraint;
            class Target extends \Exception {}
            class Subject
            {
                public function method(): void
                {
                    try {} catch (Target $e) {}
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\CatchBlock\ShouldNotConstraint\Subject', 'Fixture\Relation\Depend\CatchBlock\ShouldNotConstraint\Target'), 8],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\CatchBlock\CanOnlyConstraint;
            class Allowed extends \Exception {}
            class Target extends \Exception {}
            class Subject
            {
                public function method(): void
                {
                    try {} catch (Target $e) {}
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\CatchBlock\CanOnlyConstraint\Subject', 'Fixture\Relation\Depend\CatchBlock\CanOnlyConstraint\Target'), 9],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::ShouldNot => [
                'Fixture\Relation\Depend\CatchBlock\ShouldNotConstraint\Subject',
                'Fixture\Relation\Depend\CatchBlock\ShouldNotConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Depend\CatchBlock\CanOnlyConstraint\Subject',
                'Fixture\Relation\Depend\CatchBlock\CanOnlyConstraint\Allowed',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new CatchBlockRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
