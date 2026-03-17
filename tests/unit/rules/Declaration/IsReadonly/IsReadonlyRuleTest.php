<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\IsReadonly;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsReadonly\IsReadonlyRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsReadonlyRule>
 * @internal
 * @coversNothing
 */
class IsReadonlyRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    public function testShouldConstraint(): void
    {
        // Non-readonly class — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\IsReadonly\ShouldConstraint;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be readonly', 'Fixture\Declaration\IsReadonly\ShouldConstraint\Subject'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beReadonly',
            [new Classname('Fixture\Declaration\IsReadonly\ShouldConstraint\Subject', false)],
            []
        );

        return new IsReadonlyRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
