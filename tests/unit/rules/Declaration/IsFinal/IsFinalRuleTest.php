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

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        // Non-final class — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsFinal\ShouldConstraint;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be final', 'Fixture\Declaration\IsFinal\ShouldConstraint\Subject'), 3],
        ]);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;

        // Final class — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsFinal\ShouldNotConstraint;
            final class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be final', 'Fixture\Declaration\IsFinal\ShouldNotConstraint\Subject'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $subject = match ($this->constraint) {
            Constraint::Should => 'Fixture\Declaration\IsFinal\ShouldConstraint\Subject',
            default => 'Fixture\Declaration\IsFinal\ShouldNotConstraint\Subject',
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'beFinal',
            [new Classname($subject, false)],
            []
        );

        return new IsFinalRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
