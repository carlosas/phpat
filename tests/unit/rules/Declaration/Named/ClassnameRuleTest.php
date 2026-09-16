<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\Named;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\Named\ClassnameRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassnameRule>
 * @internal
 * @coversNothing
 */
class ClassnameRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testIgnoresUnselectedClassesWithShould(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldUnselected\SubjectOther';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'Fixture\Declaration\Named\ShouldUnselected\Other']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldUnselected;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testIgnoresUnselectedClassesWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldNotUnselected\SubjectOther';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'Fixture\Declaration\Named\ShouldNotUnselected\Subject']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldNotUnselected;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsDifferentNamesWithShould(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldExactMismatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'Fixture\Declaration\Named\ShouldExactMismatch\Other']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldExactMismatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be named Fixture\Declaration\Named\ShouldExactMismatch\Other', $subject), 5],
        ]);
    }

    public function testAcceptsMatchingNamesWithShould(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldExactMatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => $subject]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldExactMatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsNamesNotMatchingRegexWithShould(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldRegexMismatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => true, 'classname' => '/Other$/']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldRegexMismatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be named matching the regex /Other$/', $subject), 5],
        ]);
    }

    public function testAcceptsNamesMatchingRegexWithShould(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldRegexMatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => true, 'classname' => '/Subject$/']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldRegexMatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testAcceptsDifferentNamesWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldNotExactMismatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'Fixture\Declaration\Named\ShouldNotExactMismatch\Other']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldNotExactMismatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsMatchingNamesWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldNotExactMatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => $subject]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldNotExactMatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be named %s', $subject, $subject), 5],
        ]);
    }

    public function testAcceptsNamesNotMatchingRegexWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldNotRegexMismatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => true, 'classname' => '/Other$/']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldNotRegexMismatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsNamesMatchingRegexWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\Named\ShouldNotRegexMatch\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beNamed',
            [new Classname($subject, false)],
            [],
            [],
            ['isRegex' => true, 'classname' => '/Subject$/']
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\Named\ShouldNotRegexMatch;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be named matching the regex /Subject$/', $subject), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ClassnameRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
