<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldImplement;

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
class SimpleImplementedInterfacesTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldImplementSimple';
    private const SUBJECT = 'Fixture\ShouldImplement\SimpleImplementedInterfacesTest\Subject';
    private const TARGET = 'Fixture\ShouldImplement\SimpleImplementedInterfacesTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldImplement\SimpleImplementedInterfacesTest;
            interface Target {}
            class Subject implements Target {}
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'implement',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new ImplementedInterfacesRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
