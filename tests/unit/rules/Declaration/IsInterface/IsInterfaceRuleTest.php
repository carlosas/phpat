<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsInterface;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsInterface\IsInterfaceRule;
use PHPat\Selector\Selector;
use PHPat\Statement\StatementBuilder;
use PHPat\Test\PHPat;
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

    private Constraint $constraint = Constraint::Should;

    public function testShouldNotBuilder(): void
    {
        $rule = PHPat::rule()->classes(Selector::all())->shouldNot()->beInterface()();

        self::assertSame(Constraint::ShouldNot, $rule->getConstraint());
        self::assertSame('beInterface', $rule->getAssertionType());
    }

    public function testShouldConstraint(): void
    {
        // Non-interface class — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsInterface\ShouldConstraint;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be an interface', 'Fixture\Declaration\IsInterface\ShouldConstraint\Subject'), 3],
        ]);
    }

    public function testShouldAcceptsInterface(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsInterface\ShouldAcceptsInterface;
            interface Subject {}
            PHP);

        $this->analyse([$file], []);
    }

    public function testShouldNotConstraint(): void
    {
        $this->constraint = Constraint::ShouldNot;
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsInterface\ShouldNotConstraint;
            interface SubjectInterface {}
            class SubjectClass {}
            trait SubjectTrait {}
            enum SubjectEnum {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be an interface', 'Fixture\Declaration\IsInterface\ShouldNotConstraint\SubjectInterface'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'beInterface',
            [Selector::inNamespace('Fixture\Declaration\IsInterface')],
            []
        );

        return new IsInterfaceRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
