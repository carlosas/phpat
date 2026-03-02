<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\ConstantUseRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ConstantUseRule>
 * @internal
 * @coversNothing
 */
class ConstantUseTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\CanOnlyDepend\ConstantUseTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\ConstantUseTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\ConstantUseTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\ConstantUseTest;
            class Allowed {}
            class Target
            {
                public const CONSTANT = 'value';
            }
            class Subject
            {
                public function method(): string
                {
                    return Target::CONSTANT;
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
            'test',
            Constraint::CanOnly,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::ALLOWED, false)]
        );

        return new ConstantUseRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
