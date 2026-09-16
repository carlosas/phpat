<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Construct;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Construct\NewRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 * @internal
 * @coversNothing
 */
class NewRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMissingConstructionWithShould(): void
    {
        $subject = 'Fixture\Relation\Construct\NewRule\Should\Subject';
        $target = 'Fixture\Relation\Construct\NewRule\Should\Target1';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'construct',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Construct\NewRule\Should;

            class Target1
            {
            }
            class Target2
            {
            }
            class Subject
            {
                public function create(): Target2
                {
                    return new Target2();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should construct %s', $subject, $target), 15],
        ]);
    }

    public function testRejectsMatchingConstructionWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Construct\NewRule\ShouldNot\Subject';
        $target = 'Fixture\Relation\Construct\NewRule\ShouldNot\Target1';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'construct',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Construct\NewRule\ShouldNot;

            class Target1
            {
            }
            class Target2
            {
            }
            class Subject
            {
                public function create(): Target1
                {
                    return new Target1();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not construct %s', $subject, $target), 15],
        ]);
    }

    public function testRejectsDisallowedConstructionWithCanOnly(): void
    {
        $subject = 'Fixture\Relation\Construct\NewRule\CanOnly\Subject';
        $target = 'Fixture\Relation\Construct\NewRule\CanOnly\Target1';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::CanOnly,
            'construct',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Construct\NewRule\CanOnly;

            class Target1
            {
            }
            class Target2
            {
            }
            class Subject
            {
                public function run(): Target1
                {
                    $a = new Target2();
                    return new Target1();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not construct Fixture\Relation\Construct\NewRule\CanOnly\Target2', $subject), 15],
        ]);
    }

    protected function getRule(): Rule
    {
        return new NewRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
