<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsInterface;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsInterface\IsInterfaceRule;
use PHPat\Selector\Classname;
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

    private Constraint $constraint = Constraint::Should;

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

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            $this->constraint,
            'beInterface',
            [new Classname('Fixture\Declaration\IsInterface\ShouldConstraint\Subject', false)],
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
