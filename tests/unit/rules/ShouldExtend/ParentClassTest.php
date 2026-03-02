<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldExtend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Extend\ParentClassRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ParentClassRule>
 * @internal
 * @coversNothing
 */
class ParentClassTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldExtend';
    private const SUBJECT = 'Fixture\ShouldExtend\ParentClassTest\Subject';
    private const TARGET = 'Fixture\ShouldExtend\ParentClassTest\Target';

    private bool $showRuleName = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldExtend\ParentClassTest;
            class Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should extend %s', self::SUBJECT, self::TARGET), 4],
        ]);
    }

    public function testRuleWithRuleName(): void
    {
        $this->showRuleName = true;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldExtend\ParentClassTest;
            class Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s: %s should extend %s', self::RULE_NAME, self::SUBJECT, self::TARGET), 4],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'extend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new ParentClassRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, $this->showRuleName),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
