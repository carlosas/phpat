<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Relation\Depend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\DocParamTagRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DocParamTagRule>
 * @internal
 * @coversNothing
 */
class DocParamTagRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsMatchingDependenciesWithShouldNot(): void
    {
        $subject = 'Fixture\Relation\Depend\DocParamTag\ShouldNotConstraint\ShouldNot\Subject';
        $target = 'Fixture\Relation\Depend\DocParamTag\ShouldNotConstraint\ShouldNot\Target';
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

            namespace Fixture\Relation\Depend\DocParamTag\ShouldNotConstraint\ShouldNot;

            class Target
            {
            }
            class Subject
            {
                /** @param Target $p */
                public function method($p)
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
        $subject = 'Fixture\Relation\Depend\DocParamTag\ShouldNotConstraint\IgnoreDocComments\Subject';
        $target = 'Fixture\Relation\Depend\DocParamTag\ShouldNotConstraint\IgnoreDocComments\Target';
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

            namespace Fixture\Relation\Depend\DocParamTag\ShouldNotConstraint\IgnoreDocComments;

            class Target
            {
            }
            class Subject
            {
                /** @param Target $p */
                public function method($p)
                {
                }
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        return new DocParamTagRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
