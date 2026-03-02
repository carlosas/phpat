<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\AllAttributesRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<AllAttributesRule>
 * @internal
 * @coversNothing
 */
class AllAttributesRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::ShouldNot;

    public function testShouldNotConstraint(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint;
            #[\Attribute(\Attribute::TARGET_ALL)]
            class Target {}
            #[Target]
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint\Subject', 'Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint\Target'), 5],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint;
            #[\Attribute(\Attribute::TARGET_ALL)]
            class Allowed {}
            #[\Attribute(\Attribute::TARGET_ALL)]
            class Target {}
            #[Target]
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint\Subject', 'Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint\Target'), 7],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::ShouldNot => [
                'Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint\Subject',
                'Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint\Subject',
                'Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint\Allowed',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new AllAttributesRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
