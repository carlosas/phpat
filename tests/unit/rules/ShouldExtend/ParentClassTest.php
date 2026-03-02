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

    private const SUBJECT = 'Fixture\ShouldExtend\ParentClassTest\Subject';
    private const TARGET = 'Fixture\ShouldExtend\ParentClassTest\Target';

    public function testRule(): void
    {
        // Class not extending — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldExtend\ParentClassTest;
            class Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should extend %s', self::SUBJECT, self::TARGET), 4],
        ]);

        // Class correctly extending — no errors
        $subject2 = 'Fixture\ShouldExtend\SimpleParentClassTest\Subject';
        $target2 = 'Fixture\ShouldExtend\SimpleParentClassTest\Target';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldExtend\SimpleParentClassTest;
            class Target {}
            class Subject extends Target {}
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'extend',
            [new Classname($subject2, false)],
            [new Classname($target2, false)]
        );

        $rule2 = new ParentClassRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );

        $this->analyse([$file2], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
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
