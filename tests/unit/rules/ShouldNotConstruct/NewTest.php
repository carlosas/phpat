<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotConstruct;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Construct\NewRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 * @internal
 * @coversNothing
 */
class NewTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldNotConstruct';
    private const SUBJECT = 'Fixture\ShouldNotConstruct\NewTest\Subject';
    private const TARGET = 'Fixture\ShouldNotConstruct\NewTest\Target';

    private bool $showRuleName = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotConstruct\NewTest;
            class Target {}
            class Subject
            {
                public function create(): Target
                {
                    return new Target();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not construct %s', self::SUBJECT, self::TARGET), 8],
        ]);
    }

    public function testRuleWithRuleName(): void
    {
        $this->showRuleName = true;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotConstruct\NewTest;
            class Target {}
            class Subject
            {
                public function create(): Target
                {
                    return new Target();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s: %s should not construct %s', self::RULE_NAME, self::SUBJECT, self::TARGET), 8],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::ShouldNot,
            'construct',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new NewRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, $this->showRuleName),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
