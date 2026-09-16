<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsEnum;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsEnum\IsEnumRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsEnumRule>
 * @internal
 * @coversNothing
 */
class IsEnumRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsClassesWithShould(): void
    {
        $subject = 'Fixture\Declaration\IsEnum\ShouldConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beEnum',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsEnum\ShouldConstraint;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be enum', $subject), 5],
        ]);
    }

    public function testRejectsEnumsWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\IsEnum\ShouldNotConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beEnum',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsEnum\ShouldNotConstraint;

            enum Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be enum', $subject), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        return new IsEnumRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
