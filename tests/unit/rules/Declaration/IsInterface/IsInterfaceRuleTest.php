<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsInterface;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsInterface\IsInterfaceRule;
use PHPat\Selector\ClassNamespace;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInterfaceRule>
 * @internal
 * @coversNothing
 */
class IsInterfaceRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    private FakeTestParser $testParser;

    private Configuration $configuration;

    public function testRejectsClassesWithShould(): void
    {
        $subjectNamespace = 'Fixture\Declaration\IsInterface';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beInterface',
            [new ClassNamespace($subjectNamespace, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsInterface\ShouldConstraint;

            class Subject
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s\ShouldConstraint\Subject should be an interface', $subjectNamespace), 5],
        ]);
    }

    public function testAcceptsInterfacesWithShould(): void
    {
        $subjectNamespace = 'Fixture\Declaration\IsInterface';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beInterface',
            [new ClassNamespace($subjectNamespace, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsInterface\ShouldAcceptsInterface;

            interface Subject
            {
            }
            PHP);

        $this->analyse([$file], []);
    }

    public function testRejectsInterfacesWithShouldNot(): void
    {
        $subjectNamespace = 'Fixture\Declaration\IsInterface';
        $this->configuration = new Configuration(false, true, false);
        $this->testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'beInterface',
            [new ClassNamespace($subjectNamespace, false)],
            []
        );

        $file = $this->createPhpFile(<<<'PHP'
            <?php

            namespace Fixture\Declaration\IsInterface\ShouldNotConstraint;

            interface SubjectInterface
            {
            }
            class SubjectClass
            {
            }
            trait SubjectTrait
            {
            }
            enum SubjectEnum
            {
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s\ShouldNotConstraint\SubjectInterface should not be an interface', $subjectNamespace), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        return new IsInterfaceRule(
            new StatementBuilder($this->testParser),
            $this->configuration,
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
