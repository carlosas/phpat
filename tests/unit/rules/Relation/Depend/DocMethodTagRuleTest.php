<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\DocMethodTagRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DocMethodTagRule>
 * @internal
 * @coversNothing
 */
class DocMethodTagRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\DocMethodTag\ShouldNotConstraint\ShouldNot\Subject';
        $target = 'Fixture\Relation\Depend\DocMethodTag\ShouldNotConstraint\ShouldNot\Target';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Depend\DocMethodTag\ShouldNotConstraint\ShouldNot;

            class Target
            {
            }
            /** @method Target myMethod() */
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $subject, $target), 9],
        ]);
    }

    public function testIgnoresDocCommentsWhenConfigured(): void
    {
        $subject = 'Fixture\Relation\Depend\DocMethodTag\ShouldNotConstraint\IgnoreDocComments\Subject';
        $target = 'Fixture\Relation\Depend\DocMethodTag\ShouldNotConstraint\IgnoreDocComments\Target';
        $this->configuration = new Configuration(true, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'depend',
            [new Classname($subject, false)],
            [new Classname($target, false)]
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Relation\Depend\DocMethodTag\ShouldNotConstraint\IgnoreDocComments;

            class Target
            {
            }
            /** @method Target myMethod() */
            class Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new DocMethodTagRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
