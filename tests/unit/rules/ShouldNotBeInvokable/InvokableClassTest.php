<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotBeInvokable;

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

    public const RULE_NAME = 'testShouldNotBeInvokable';
    private const SUBJECT = 'Fixture\ShouldNotBeInvokable\InvokableClassTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotBeInvokable\InvokableClassTest;
            class Subject
            {
                public function __invoke() {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not be invokable', self::SUBJECT), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::ShouldNot,
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
