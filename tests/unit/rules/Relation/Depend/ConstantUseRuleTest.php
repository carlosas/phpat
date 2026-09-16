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

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\ConstantUse\ShouldNotConstraint\Subject';
        $target = 'Fixture\Relation\Depend\ConstantUse\ShouldNotConstraint\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

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
            [sprintf('%s should not depend on %s', $subject, $target), 13],
        ]);
    }

    public function testRejectsDisallowedDependenciesWithCanOnly(): void
    {
        $subject = 'Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint\Subject';
        $target = 'Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint\Allowed';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::CanOnly,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

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
            [sprintf('%s should not depend on Fixture\Relation\Depend\ConstantUse\CanOnlyConstraint\Target', $subject), 17],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ConstantUseRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
