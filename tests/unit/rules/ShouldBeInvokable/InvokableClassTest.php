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

    public const RULE_NAME = 'testShouldBeInvokable';
    private const SUBJECT = 'Fixture\ShouldBeInvokable\InvokableClassTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeInvokable\InvokableClassTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be invokable', self::SUBJECT), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
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
