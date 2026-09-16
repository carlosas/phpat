<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\OnePublicMethodNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethodNamed\HasOnlyOnePublicMethodNamedRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodNamedRule>
 * @internal
 * @coversNothing
 */
class HasOnlyOnePublicMethodNamedRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsClassesWithoutMethodsWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactZero\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactZero;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named run', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnlyAConstructorWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactConstructorOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactConstructorOnly;

            class Subject
            {
                public function __construct()
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named run', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithOnePublicMethodWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactOne;

            class Subject
            {
                public function run(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithOnePublicMethodAndOtherMembersWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactConstructorAndOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactConstructorAndOne;

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

    public function testRejectsClassesWithMultiplePublicMethodsWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactMultiple\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactMultiple;

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
            [sprintf('%s should have only one public method named run', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnlyNonPublicMethodsWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactNonPublicOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactNonPublicOnly;

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
            [sprintf('%s should have only one public method named run', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOneDifferentlyNamedMethodWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactOneDifferentName\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactOneDifferentName;

            class Subject
            {
                public function other(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named run', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithMultipleMatchingMethodsWithExactNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldExactMultipleMatching\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldExactMultipleMatching;

            class Subject
            {
                public function run(): void
                {
                }
                public function runAgain(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named run', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithoutMethodsWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexZero\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexZero;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnlyAConstructorWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexConstructorOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexConstructorOnly;

            class Subject
            {
                public function __construct()
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithOnePublicMethodWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexOne;

            class Subject
            {
                public function run(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithOnePublicMethodAndOtherMembersWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexConstructorAndOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexConstructorAndOne;

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

    public function testRejectsClassesWithMultiplePublicMethodsWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexMultiple\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexMultiple;

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
            [sprintf('%s should have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnlyNonPublicMethodsWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexNonPublicOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexNonPublicOnly;

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
            [sprintf('%s should have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOneDifferentlyNamedMethodWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexOneDifferentName\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexOneDifferentName;

            class Subject
            {
                public function other(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithMultipleMatchingMethodsWithRegexNameWithShould(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldRegexMultipleMatching\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldRegexMultipleMatching;

            class Subject
            {
                public function run(): void
                {
                }
                public function runAgain(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithoutMethodsWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactZero\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactZero;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithOnlyAConstructorWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactConstructorOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactConstructorOnly;

            class Subject
            {
                public function __construct()
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsClassesWithOnePublicMethodWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactOne;

            class Subject
            {
                public function run(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not have only one public method named run', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnePublicMethodAndOtherMembersWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactConstructorAndOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactConstructorAndOne;

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
            [sprintf('%s should not have only one public method named run', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithMultiplePublicMethodsWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactMultiple\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactMultiple;

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

    public function testAcceptsClassesWithOnlyNonPublicMethodsWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactNonPublicOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactNonPublicOnly;

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

    public function testAcceptsClassesWithOneDifferentlyNamedMethodWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactOneDifferentName\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactOneDifferentName;

            class Subject
            {
                public function other(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithMultipleMatchingMethodsWithExactNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactMultipleMatching\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => 'run', 'isRegex' => false]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotExactMultipleMatching;

            class Subject
            {
                public function run(): void
                {
                }
                public function runAgain(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithoutMethodsWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexZero\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexZero;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithOnlyAConstructorWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexConstructorOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexConstructorOnly;

            class Subject
            {
                public function __construct()
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsClassesWithOnePublicMethodWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexOne;

            class Subject
            {
                public function run(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testRejectsClassesWithOnePublicMethodAndOtherMembersWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexConstructorAndOne\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexConstructorAndOne;

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
            [sprintf('%s should not have only one public method named /^run/', $subject), 5],
        ]);
    }

    public function testAcceptsClassesWithMultiplePublicMethodsWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexMultiple\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexMultiple;

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

    public function testAcceptsClassesWithOnlyNonPublicMethodsWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexNonPublicOnly\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexNonPublicOnly;

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

    public function testAcceptsClassesWithOneDifferentlyNamedMethodWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexOneDifferentName\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexOneDifferentName;

            class Subject
            {
                public function other(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsClassesWithMultipleMatchingMethodsWithRegexNameWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexMultipleMatching\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'haveOnlyOnePublicMethodNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['name' => '/^run/', 'isRegex' => true]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\OnePublicMethodNamed\ShouldNotRegexMultipleMatching;

            class Subject
            {
                public function run(): void
                {
                }
                public function runAgain(): void
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new HasOnlyOnePublicMethodNamedRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
