<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\DocVarTagRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DocVarTagRule>
 * @internal
 * @coversNothing
 */
class DocVarTagRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\DocVarTag\ShouldNotConstraint\ShouldNot\Subject';
        $target = 'Fixture\Relation\Depend\DocVarTag\ShouldNotConstraint\ShouldNot\Target';
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

            namespace Fixture\Relation\Depend\DocVarTag\ShouldNotConstraint\ShouldNot;

            class Target
            {
            }
            class Subject
            {
                public function method(): void
                {
                    /** @var Target $var */
                    $var = null;
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $subject, $target), 13],
        ]);
    }

    public function testIgnoresDocCommentsWhenConfigured(): void
    {
        $subject = 'Fixture\Relation\Depend\DocVarTag\ShouldNotConstraint\IgnoreDocComments\Subject';
        $target = 'Fixture\Relation\Depend\DocVarTag\ShouldNotConstraint\IgnoreDocComments\Target';
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

            namespace Fixture\Relation\Depend\DocVarTag\ShouldNotConstraint\IgnoreDocComments;

            class Target
            {
            }
            class Subject
            {
                public function method(): void
                {
                    /** @var Target $var */
                    $var = null;
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new DocVarTagRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
