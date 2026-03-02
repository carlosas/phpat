<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotImplement;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Implement\ImplementedInterfacesRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ImplementedInterfacesRule>
 * @internal
 * @coversNothing
 */
class ImplementedInterfacesTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldNotImplement';
    private const SUBJECT = 'Fixture\ShouldNotImplement\ImplementedInterfacesTest\Subject';
    private const TARGET = 'Fixture\ShouldNotImplement\ImplementedInterfacesTest\Target';

    private bool $showRuleName = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotImplement\ImplementedInterfacesTest;
            interface Target {}
            class Subject implements Target {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not implement %s', self::SUBJECT, self::TARGET), 4],
        ]);
    }

    public function testRuleWithRuleName(): void
    {
        $this->showRuleName = true;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotImplement\ImplementedInterfacesTest;
            interface Target {}
            class Subject implements Target {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s: %s should not implement %s', self::RULE_NAME, self::SUBJECT, self::TARGET), 4],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::ShouldNot,
            'implement',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new ImplementedInterfacesRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, $this->showRuleName),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
