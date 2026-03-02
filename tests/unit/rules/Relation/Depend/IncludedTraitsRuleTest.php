<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\IncludedTraitsRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IncludedTraitsRule>
 * @internal
 * @coversNothing
 */
class IncludedTraitsRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::ShouldNot;

    public function testShouldNotConstraint(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\IncludedTraits\ShouldNotConstraint;
            trait Target {}
            class Subject
            {
                use Target;
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\IncludedTraits\ShouldNotConstraint\Subject', 'Fixture\Relation\Depend\IncludedTraits\ShouldNotConstraint\Target'), 4],
        ]);
    }

    public function testCanOnlyConstraint(): void
    {
        $this->constraint = Constraint::CanOnly;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\Depend\IncludedTraits\CanOnlyConstraint;
            trait Allowed {}
            trait Target {}
            class Subject
            {
                use Target;
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', 'Fixture\Relation\Depend\IncludedTraits\CanOnlyConstraint\Subject', 'Fixture\Relation\Depend\IncludedTraits\CanOnlyConstraint\Target'), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::ShouldNot => [
                'Fixture\Relation\Depend\IncludedTraits\ShouldNotConstraint\Subject',
                'Fixture\Relation\Depend\IncludedTraits\ShouldNotConstraint\Target',
            ],
            default => [
                'Fixture\Relation\Depend\IncludedTraits\CanOnlyConstraint\Subject',
                'Fixture\Relation\Depend\IncludedTraits\CanOnlyConstraint\Allowed',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new IncludedTraitsRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
