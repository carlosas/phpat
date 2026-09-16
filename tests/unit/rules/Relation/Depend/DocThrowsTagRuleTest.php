<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\DocThrowsTagRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DocThrowsTagRule>
 * @internal
 * @coversNothing
 */
class DocThrowsTagRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\DocThrowsTag\ShouldNotConstraint\ShouldNot\Subject';
        $target = 'Fixture\Relation\Depend\DocThrowsTag\ShouldNotConstraint\ShouldNot\Target';
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

            namespace Fixture\Relation\Depend\DocThrowsTag\ShouldNotConstraint\ShouldNot;

            class Target extends \Exception
            {
            }
            class Subject
            {
                /** @throws Target */
                public function method()
                {
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $subject, $target), 11],
        ]);
    }

    public function testIgnoresDocCommentsWhenConfigured(): void
    {
        $subject = 'Fixture\Relation\Depend\DocThrowsTag\ShouldNotConstraint\IgnoreDocComments\Subject';
        $target = 'Fixture\Relation\Depend\DocThrowsTag\ShouldNotConstraint\IgnoreDocComments\Target';
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

            namespace Fixture\Relation\Depend\DocThrowsTag\ShouldNotConstraint\IgnoreDocComments;

            class Target extends \Exception
            {
            }
            class Subject
            {
                /** @throws Target */
                public function method()
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new DocThrowsTagRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
