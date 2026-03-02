<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\AllAttributesRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<AllAttributesRule>
 * @internal
 * @coversNothing
 */
class AllAttributeTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldNotDependAllAttribute';
    private const SUBJECT = 'Fixture\ShouldNotDepend\AllAttributeTest\Subject';
    private const TARGET = 'Fixture\ShouldNotDepend\AllAttributeTest\Target';

    private bool $showRuleName = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\AllAttributeTest;
            #[\Attribute(\Attribute::TARGET_ALL)]
            class Target {}
            #[Target]
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 5],
        ]);
    }

    public function testRuleWithRuleName(): void
    {
        $this->showRuleName = true;
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\AllAttributeTest;
            #[\Attribute(\Attribute::TARGET_ALL)]
            class Target {}
            #[Target]
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s: %s should not depend on %s', self::RULE_NAME, self::SUBJECT, self::TARGET), 5],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::ShouldNot,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new AllAttributesRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, $this->showRuleName),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
