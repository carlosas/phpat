<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Implement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Implement\ImplementedInterfacesRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ImplementedInterfacesRule>
 * @internal
 * @coversNothing
 */
class ImplementedInterfacesRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMissingInterfacesWithShould(): void
    {
        $subject = 'Fixture\Relation\Implement\ShouldConstraint\Subject';
        $target = 'Fixture\Relation\Implement\ShouldConstraint\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'implement',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Implement\ShouldConstraint;

            interface Target
            {
            }
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should implement %s', $subject, $target), 8],
        ]);
    }

    public function testAcceptsMatchingInterfacesWithShould(): void
    {
        $subject = 'Fixture\Relation\Implement\ShouldConstraintPass\Subject';
        $target = 'Fixture\Relation\Implement\ShouldConstraintPass\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'implement',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Implement\ShouldConstraintPass;

            interface Target
            {
            }
            class Subject implements Target
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsMatchingInterfacesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Implement\ShouldNotConstraint\Subject';
        $target = 'Fixture\Relation\Implement\ShouldNotConstraint\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'implement',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Implement\ShouldNotConstraint;

            interface Target
            {
            }
            class Subject implements Target
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not implement %s', $subject, $target), 8],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ImplementedInterfacesRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
