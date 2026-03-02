<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\Declaration\Exists;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\Exists\ExistsRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ExistsRule>
 * @internal
 * @coversNothing
 */
class ExistsRuleTest extends RuleTestCase
{
    use CreatesPhpFile;

    public function testShouldNotConstraint(): void
    {
        // Existing class — error expected when ShouldNot
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\Declaration\Exists\ShouldNotConstraint;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not exist', 'Fixture\Declaration\Exists\ShouldNotConstraint\Subject'), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'exist',
            [new Classname('Fixture\Declaration\Exists\ShouldNotConstraint\Subject', false)],
            []
        );

        return new ExistsRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
