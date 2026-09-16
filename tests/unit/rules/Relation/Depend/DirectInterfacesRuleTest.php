<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\DirectInterfacesRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DirectInterfacesRule>
 * @internal
 * @coversNothing
 */
class DirectInterfacesRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\DirectInterfaces\ShouldNotConstraint\Subject';
        $target = 'Fixture\Relation\Depend\DirectInterfaces\ShouldNotConstraint\Target';
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

            namespace Fixture\Relation\Depend\DirectInterfaces\ShouldNotConstraint;

            interface Target
            {
            }
            class Subject implements Target
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $subject, $target), 8],
        ]);
    }

    public function testRejectsDisallowedDependenciesWithCanOnly(): void
    {
        $subject = 'Fixture\Relation\Depend\DirectInterfaces\CanOnlyConstraint\Subject';
        $target = 'Fixture\Relation\Depend\DirectInterfaces\CanOnlyConstraint\Allowed';
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

            namespace Fixture\Relation\Depend\DirectInterfaces\CanOnlyConstraint;

            interface Allowed
            {
            }
            interface Target
            {
            }
            class Subject implements Target
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on Fixture\Relation\Depend\DirectInterfaces\CanOnlyConstraint\Target', $subject), 11],
        ]);
    }

    protected function getRule(): Rule
    {
        return new DirectInterfacesRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
