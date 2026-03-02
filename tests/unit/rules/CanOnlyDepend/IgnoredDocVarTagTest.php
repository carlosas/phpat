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
class IgnoredDocVarTagTest extends RuleTestCase
{
    use CreatesPhpFile;

    public const RULE_NAME = 'testCanOnlyDependIgnoredDocVarTag';
    private const SUBJECT = 'Fixture\CanOnlyDepend\IgnoredDocVarTagTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\IgnoredDocVarTagTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\IgnoredDocVarTagTest\Target';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\IgnoredDocVarTagTest;
            class Allowed {}
            class Target {}
            class Subject
            {
                /** @var Target */
                public $prop;
            }
            PHP);

        $this->analyse([$file], []);
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
            new Configuration(true, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
