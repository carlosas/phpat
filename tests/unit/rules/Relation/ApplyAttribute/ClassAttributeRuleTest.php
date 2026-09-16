<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\ApplyAttribute;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\ApplyAttribute\ClassAttributeRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassAttributeRule>
 * @internal
 * @coversNothing
 */
class ClassAttributeRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsClassesWithoutAttributesWithShould(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldAbsent\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldAbsent\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldAbsent;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should apply the attribute %s', $subject, $target), 13],
        ]);
    }

    public function testAcceptsMatchingAttributesWithShould(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldPresent\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldPresent\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldPresent;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            #[Target]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsOtherAttributesWithShould(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldOther\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldOther\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldOther;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            #[Other]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should apply the attribute %s', $subject, $target), 13],
        ]);
    }

    public function testAcceptsMatchingAttributesAlongsideOthersWithShould(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldBoth\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldBoth\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldBoth;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            #[Target, Other]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithoutAttributesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldNotAbsent\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldNotAbsent\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldNotAbsent;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsMatchingAttributesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldNotPresent\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldNotPresent\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldNotPresent;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            #[Target]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not apply the attribute %s', $subject, $target), 13],
        ]);
    }

    public function testAcceptsOtherAttributesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldNotOther\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldNotOther\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldNotOther;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            #[Other]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsMatchingAttributesAlongsideOthersWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldNotBoth\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldNotBoth\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldNotBoth;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            #[Target, Other]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not apply the attribute %s', $subject, $target), 13],
        ]);
    }

    public function testAcceptsExcludedAttributesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\ApplyAttribute\ShouldNotExcluded\Subject';
        $target = 'Fixture\Relation\ApplyAttribute\ShouldNotExcluded\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'applyAttribute',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );
        $this->testParser->targetExcludes = [new Classname($target, false)];

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\ApplyAttribute\ShouldNotExcluded;

            #[\Attribute]
            class Target
            {
            }
            #[\Attribute]
            class Other
            {
            }
            #[Target]
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new ClassAttributeRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
