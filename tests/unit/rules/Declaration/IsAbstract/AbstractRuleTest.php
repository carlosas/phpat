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

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsNonAbstractClassesWithShould(): void
    {
        $subject = 'Fixture\Declaration\IsAbstract\ShouldConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beAbstract',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsAbstract\ShouldConstraint;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be abstract', $subject), 5],
        ]);
    }

    public function testRejectsAbstractClassesWithShouldNot(): void
    {
        $subject = 'Fixture\Declaration\IsAbstract\ShouldNotConstraint\Subject';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beAbstract',
            [new Classname($subject, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsAbstract\ShouldNotConstraint;

            abstract class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be abstract', $subject), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        return new AbstractRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
