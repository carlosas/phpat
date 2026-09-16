<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsInvokable;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsInvokable\IsInvokableRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInvokableRule>
 * @internal
 * @coversNothing
 */
class IsInvokableRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsNonInvokableClassesWithShould(): void
    {
        $subject = 'Fixture\Declaration\IsInvokable\ShouldConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beInvokable',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsInvokable\ShouldConstraint;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be invokable', $subject), 5],
        ]);
    }

    public function testAcceptsInvokableClassesWithShould(): void
    {
        $subject = 'Fixture\Declaration\IsInvokable\ShouldConstraintPass\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beInvokable',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsInvokable\ShouldConstraintPass;

            class Subject
            {
                public function __invoke()
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsInvokableClassesWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\IsInvokable\ShouldNotConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beInvokable',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsInvokable\ShouldNotConstraint;

            class Subject
            {
                public function __invoke()
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be invokable', $subject), 5],
        ]);
    }

    public function testAcceptsNonInvokableClassesWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\IsInvokable\ShouldNotConstraintPass\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beInvokable',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsInvokable\ShouldNotConstraintPass;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new IsInvokableRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
