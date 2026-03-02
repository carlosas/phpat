<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsAbstract;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsAbstract\AbstractRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<AbstractRule>
 * @internal
 * @coversNothing
 */
class AbstractRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        // Non-abstract class — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsAbstract\ShouldConstraint;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be abstract', 'Fixture\Declaration\IsAbstract\ShouldConstraint\Subject'), 3],
        ]);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;

        // Abstract class — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsAbstract\ShouldNotConstraint;
            abstract class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be abstract', 'Fixture\Declaration\IsAbstract\ShouldNotConstraint\Subject'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $subject = match ($this->constraint) {
            Constraint::Should => 'Fixture\Declaration\IsAbstract\ShouldConstraint\Subject',
            default => 'Fixture\Declaration\IsAbstract\ShouldNotConstraint\Subject',
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'beAbstract',
            [new Classname($subject, false)],
            []
        );

        return new AbstractRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
