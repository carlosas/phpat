<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\MethodReturnRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<MethodReturnRule>
 * @internal
 * @coversNothing
 */
class MethodReturnTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testShouldNotDependMethodReturn';
    private const SUBJECT = 'Fixture\ShouldNotDepend\MethodReturnTest\Subject';
    private const TARGET = 'Fixture\ShouldNotDepend\MethodReturnTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\MethodReturnTest;
            class Target {}
            class Subject
            {
                public function method(): Target {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 6],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::ShouldNot,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new MethodReturnRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
