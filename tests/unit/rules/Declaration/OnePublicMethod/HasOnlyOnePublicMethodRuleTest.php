<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\OnePublicMethod;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethod\HasOnlyOnePublicMethodRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodRule>
 * @internal
 * @coversNothing
 */
class HasOnlyOnePublicMethodRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsClassesWithoutMethodsWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldZero\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldZero;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnlyAConstructorWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldConstructorOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldConstructorOnly;

            class Subject
            {
                public function __construct()
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithOnePublicMethodWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldOne;

            class Subject
            {
                public function run(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithOnePublicMethodAndOtherMembersWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldConstructorAndOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldConstructorAndOne;

            class Subject
            {
                public function __construct()
                {
                }
                public function run(): void
                {
                }
                private function helper(): void
                {
                }
                protected function support(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsClassesWithMultiplePublicMethodsWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldMultiple\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldMultiple;

            class Subject
            {
                public function run(): void
                {
                }
                public function other(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnlyNonPublicMethodsWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldNonPublicOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldNonPublicOnly;

            class Subject
            {
                private function helper(): void
                {
                }
                protected function support(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithoutMethodsWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldNotZero\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldNotZero;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithOnlyAConstructorWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldNotConstructorOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldNotConstructorOnly;

            class Subject
            {
                public function __construct()
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsClassesWithOnePublicMethodWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldNotOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldNotOne;

            class Subject
            {
                public function run(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not have only one public method', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnePublicMethodAndOtherMembersWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldNotConstructorAndOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldNotConstructorAndOne;

            class Subject
            {
                public function __construct()
                {
                }
                public function run(): void
                {
                }
                private function helper(): void
                {
                }
                protected function support(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not have only one public method', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithMultiplePublicMethodsWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldNotMultiple\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldNotMultiple;

            class Subject
            {
                public function run(): void
                {
                }
                public function other(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithOnlyNonPublicMethodsWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethod\ShouldNotNonPublicOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethod',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethod\ShouldNotNonPublicOnly;

            class Subject
            {
                private function helper(): void
                {
                }
                protected function support(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new HasOnlyOnePublicMethodRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
