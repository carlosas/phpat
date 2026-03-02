<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\IncludeTrait;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\IncludeTrait\IncludedTraitsRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IncludedTraitsRule>
 * @internal
 * @coversNothing
 */
class IncludedTraitsRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private Constraint $constraint = Constraint::Should;

    public function testShouldConstraint(): void
    {
        // Class not using trait — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\IncludeTrait\ShouldConstraint;
            trait Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should include %s', 'Fixture\Relation\IncludeTrait\ShouldConstraint\Subject', 'Fixture\Relation\IncludeTrait\ShouldConstraint\Target'), 4],
        ]);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;

        // Class using trait — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Relation\IncludeTrait\ShouldNotConstraint;
            trait Target {}
            class Subject
            {
                use Target;
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not include %s', 'Fixture\Relation\IncludeTrait\ShouldNotConstraint\Subject', 'Fixture\Relation\IncludeTrait\ShouldNotConstraint\Target'), 4],
        ]);
    }

    protected function getRule(): Rule
    {
        [$subject, $target] = match ($this->constraint) {
            Constraint::Should => [
                'Fixture\Relation\IncludeTrait\ShouldConstraint\Subject',
                'Fixture\Relation\IncludeTrait\ShouldConstraint\Target',
            ],
            default => [
                'Fixture\Relation\IncludeTrait\ShouldNotConstraint\Subject',
                'Fixture\Relation\IncludeTrait\ShouldNotConstraint\Target',
            ],
        };

        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'include',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        return new IncludedTraitsRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
