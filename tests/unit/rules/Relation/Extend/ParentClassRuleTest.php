<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Extend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Extend\ParentClassRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ParentClassRule>
 * @internal
 * @coversNothing
 */
class ParentClassRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMissingParentClassesWithShould(): void
    {
        $subject = 'Fixture\Relation\Extend\ShouldConstraint\Subject';
        $target = 'Fixture\Relation\Extend\ShouldConstraint\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'extend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Extend\ShouldConstraint;

            class Target
            {
            }
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should extend %s', $subject, $target), 8],
        ]);
    }

    public function testAcceptsMatchingParentClassesWithShould(): void
    {
        $subject = 'Fixture\Relation\Extend\ShouldConstraintPass\Subject';
        $target = 'Fixture\Relation\Extend\ShouldConstraintPass\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'extend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Extend\ShouldConstraintPass;

            class Target
            {
            }
            class Subject extends Target
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsMatchingParentClassesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Extend\ShouldNotConstraint\Subject';
        $target = 'Fixture\Relation\Extend\ShouldNotConstraint\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'extend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Extend\ShouldNotConstraint;

            class Target
            {
            }
            class Subject extends Target
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not extend %s', $subject, $target), 8],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ParentClassRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
