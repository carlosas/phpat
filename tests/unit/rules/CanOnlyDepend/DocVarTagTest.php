<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\DocVarTagRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DocVarTagRule>
 * @internal
 * @coversNothing
 */
class DocVarTagTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testCanOnlyDependDocVarTag';
    private const SUBJECT = 'Fixture\CanOnlyDepend\DocVarTagTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\DocVarTagTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\DocVarTagTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\DocVarTagTest;
            class Allowed {}
            class Target {}
            class Subject
            {
                public function method(): void
                {
                    /** @var Target $var */
                    $var = null;
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 10],
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

        return new DocVarTagRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
