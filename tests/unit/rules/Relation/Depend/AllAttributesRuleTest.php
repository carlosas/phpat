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

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint\Subject';
        $target = 'Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint\Target';
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

            namespace Fixture\Relation\Depend\AllAttributes\ShouldNotConstraint;

            #[\Attribute(\Attribute::TARGET_ALL)]
            class Target
            {
            }
            #[Target]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $subject, $target), 9],
        ]);
    }

    public function testRejectsDisallowedDependenciesWithCanOnly(): void
    {
        $subject = 'Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint\Subject';
        $target = 'Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint\Allowed';
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

            namespace Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint;

            #[\Attribute(\Attribute::TARGET_ALL)]
            class Allowed
            {
            }
            #[\Attribute(\Attribute::TARGET_ALL)]
            class Target
            {
            }
            #[Target]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on Fixture\Relation\Depend\AllAttributes\CanOnlyConstraint\Target', $subject), 13],
        ]);
    }

    protected function getRule(): Rule
    {
        return new AllAttributesRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
