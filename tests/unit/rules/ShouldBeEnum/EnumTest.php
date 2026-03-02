<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeEnum;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsEnum\IsEnumRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsEnumRule>
 * @internal
 * @coversNothing
 */
class EnumTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldBeEnum';
    private const SUBJECT = 'Fixture\ShouldBeEnum\EnumTest\Subject';

    private bool $showRuleName = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeEnum\EnumTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be enum', self::SUBJECT), 3],
        ]);
    }

    public function testRuleWithRuleName(): void
    {
        $this->showRuleName = true;
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeEnum\EnumTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s: %s should be enum', self::RULE_NAME, self::SUBJECT), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'beEnum',
            [new Classname(self::SUBJECT, false)],
            []
        );

        return new IsEnumRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, $this->showRuleName),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
