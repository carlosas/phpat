<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeReadonly;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\IsReadonly\IsReadonlyRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<IsReadonlyRule>
 * @internal
 * @coversNothing
 */
class ReadonlyClassTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldBeReadonly';
    private const SUBJECT = 'Fixture\ShouldBeReadonly\ReadonlyClassTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeReadonly\ReadonlyClassTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be readonly', self::SUBJECT), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::Should,
            'beReadonly',
            [new Classname(self::SUBJECT, false)],
            []
        );

        return new IsReadonlyRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
