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

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMissingTraitsWithShould(): void
    {
        $subject = 'Fixture\Relation\IncludeTrait\ShouldConstraint\Subject';
        $target = 'Fixture\Relation\IncludeTrait\ShouldConstraint\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'include',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\IncludeTrait\ShouldConstraint;

            trait Target
            {
            }
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should include %s', $subject, $target), 8],
        ]);
    }

    public function testRejectsMatchingTraitsWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\IncludeTrait\ShouldNotConstraint\Subject';
        $target = 'Fixture\Relation\IncludeTrait\ShouldNotConstraint\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'include',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\IncludeTrait\ShouldNotConstraint;

            trait Target
            {
            }
            class Subject
            {
                use Target;
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not include %s', $subject, $target), 8],
        ]);
    }

    protected function getRule(): Rule
    {
        return new IncludedTraitsRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
