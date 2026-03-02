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
class ImplementedInterfacesTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldImplement\ImplementedInterfacesTest\Subject';
    private const TARGET = 'Fixture\ShouldImplement\ImplementedInterfacesTest\Target';

    public function testRule(): void
    {
        // Class not implementing — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldImplement\ImplementedInterfacesTest;
            interface Target {}
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should implement %s', self::SUBJECT, self::TARGET), 4],
        ]);

        // Class correctly implementing — no errors
        $subject2 = 'Fixture\ShouldImplement\SimpleImplementedInterfacesTest\Subject';
        $target2 = 'Fixture\ShouldImplement\SimpleImplementedInterfacesTest\Target';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldImplement\SimpleImplementedInterfacesTest;
            interface Target {}
            class Subject implements Target {}
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'implement',
            [new Classname($subject2, false)],
            [new Classname($target2, false)]
        );

        $rule2 = new ImplementedInterfacesRule(
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
