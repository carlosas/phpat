<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\CatchBlockRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<CatchBlockRule>
 * @internal
 * @coversNothing
 */
class CatchBlockTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testCanOnlyDependCatchBlock';
    private const SUBJECT = 'Fixture\CanOnlyDepend\CatchBlockTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\CatchBlockTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\CatchBlockTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\CatchBlockTest;
            class Allowed extends \Exception {}
            class Target extends \Exception {}
            class Subject
            {
                public function method(): void
                {
                    try {} catch (Target $e) {}
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 9],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::CanOnly,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::ALLOWED, false)]
        );

        return new CatchBlockRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
