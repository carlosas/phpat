<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\StaticMethodRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<StaticMethodRule>
 * @internal
 * @coversNothing
 */
class StaticCallTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testCanOnlyDependStaticCall';
    private const SUBJECT = 'Fixture\CanOnlyDepend\StaticCallTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\StaticCallTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\StaticCallTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\StaticCallTest;
            class Allowed {}
            class Target
            {
                public static function staticMethod(): void {}
            }
            class Subject
            {
                public function method(): void
                {
                    Target::staticMethod();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 12],
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

        return new StaticMethodRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
