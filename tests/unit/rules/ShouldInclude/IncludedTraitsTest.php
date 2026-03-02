<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldInclude;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\IncludeTrait\IncludedTraitsRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IncludedTraitsRule>
 * @internal
 * @coversNothing
 */
class IncludedTraitsTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldInclude';
    private const SUBJECT = 'Fixture\ShouldInclude\IncludedTraitsTest\Subject';
    private const TARGET = 'Fixture\ShouldInclude\IncludedTraitsTest\Target';

    private bool $showRuleName = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldInclude\IncludedTraitsTest;
            trait Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should include %s', self::SUBJECT, self::TARGET), 4],
        ]);
    }

    public function testRuleWithRuleName(): void
    {
        $this->showRuleName = true;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldInclude\IncludedTraitsTest;
            trait Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s: %s should include %s', self::RULE_NAME, self::SUBJECT, self::TARGET), 4],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'include',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new IncludedTraitsRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, $this->showRuleName),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
