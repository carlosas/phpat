<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeInvokable;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsInvokable\IsInvokableRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsInvokableRule>
 * @internal
 * @coversNothing
 */
class InvokableClassTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldBeInvokable\InvokableClassTest\Subject';

    public function testRule(): void
    {
        // Class without __invoke — error expected
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeInvokable\InvokableClassTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be invokable', self::SUBJECT), 3],
        ]);

        // Class with __invoke — no errors
        $subject2 = 'Fixture\ShouldBeInvokable\GoodImplementationInvokableClassTest\Subject';

        $file2 = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeInvokable\GoodImplementationInvokableClassTest;
            class Subject
            {
                public function __invoke() {}
            }
            PHP);

        $testParser2 = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beInvokable',
            [new Classname($subject2, false)],
            []
        );

        $rule2 = new IsInvokableRule(
            new StatementBuilder($testParser2),
            new Configuration(false, true, true),
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
            'beInvokable',
            [new Classname(self::SUBJECT, false)],
            []
        );

        return new IsInvokableRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
