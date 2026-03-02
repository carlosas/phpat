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
class SimpleParentClassTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldExtendSimple';
    private const SUBJECT = 'Fixture\ShouldExtend\SimpleParentClassTest\Subject';
    private const TARGET = 'Fixture\ShouldExtend\SimpleParentClassTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldExtend\SimpleParentClassTest;
            class Target {}
            class Subject extends Target {}
            PHP);

        $this->analyse([$file], []);
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
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
