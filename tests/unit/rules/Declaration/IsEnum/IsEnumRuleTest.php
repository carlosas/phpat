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

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        // Non-enum class — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsEnum\ShouldConstraint;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be enum', 'Fixture\Declaration\IsEnum\ShouldConstraint\Subject'), 3],
        ]);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;

        // Enum — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsEnum\ShouldNotConstraint;
            enum Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be enum', 'Fixture\Declaration\IsEnum\ShouldNotConstraint\Subject'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $subject = match ($this->constraint) {
            Constraint::Should => 'Fixture\Declaration\IsEnum\ShouldConstraint\Subject',
            default => 'Fixture\Declaration\IsEnum\ShouldNotConstraint\Subject',
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'beEnum',
            [new Classname($subject, false)],
            []
        );

        return new IsEnumRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
