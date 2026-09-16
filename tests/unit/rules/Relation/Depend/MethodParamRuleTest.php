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

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\MethodParam\ShouldNotConstraint\Subject';
        $target = 'Fixture\Relation\Depend\MethodParam\ShouldNotConstraint\Target';
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

            namespace Fixture\Relation\Depend\MethodParam\ShouldNotConstraint;

            class Target
            {
            }
            class Subject
            {
                public function method(Target $p)
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $subject, $target), 10],
        ]);
    }

    public function testRejectsDisallowedDependenciesWithCanOnly(): void
    {
        $subject = 'Fixture\Relation\Depend\MethodParam\CanOnlyConstraint\Subject';
        $target = 'Fixture\Relation\Depend\MethodParam\CanOnlyConstraint\Allowed';
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

            namespace Fixture\Relation\Depend\MethodParam\CanOnlyConstraint;

            class Allowed
            {
            }
            class Target
            {
            }
            class Subject
            {
                public function method(Target $p)
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on Fixture\Relation\Depend\MethodParam\CanOnlyConstraint\Target', $subject), 13],
        ]);
    }

    protected function getRule(): Rule
    {
        return new MethodParamRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
