<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsFinal;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsFinal\IsFinalRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsFinalRule>
 * @internal
 * @coversNothing
 */
class IsFinalRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsNonFinalClassesWithShould(): void
    {
        $subject = 'Fixture\Declaration\IsFinal\ShouldConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beFinal',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsFinal\ShouldConstraint;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be final', $subject), 5],
        ]);
    }

    public function testRejectsFinalClassesWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\IsFinal\ShouldNotConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beFinal',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsFinal\ShouldNotConstraint;

            final class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be final', $subject), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        return new IsFinalRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
