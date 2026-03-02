<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeFinal;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsFinal\IsFinalRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsFinalRule>
 * @internal
 * @coversNothing
 */
class FinalClassTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldBeFinal';
    private const SUBJECT = 'Fixture\ShouldBeFinal\FinalClassTest\Subject';

    private bool $showRuleName = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeFinal\FinalClassTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be final', self::SUBJECT), 3],
        ]);
    }

    public function testRuleWithRuleName(): void
    {
        $this->showRuleName = true;
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeFinal\FinalClassTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s: %s should be final', self::RULE_NAME, self::SUBJECT), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'beFinal',
            [new Classname(self::SUBJECT, false)],
            []
        );

        return new IsFinalRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, $this->showRuleName),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
