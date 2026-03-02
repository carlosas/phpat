<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

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

    private const SUBJECT = 'Fixture\ShouldNotDepend\DocVarTagTest\Subject';
    private const TARGET = 'Fixture\ShouldNotDepend\DocVarTagTest\Target';

    private bool $ignoreDocBlocks = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\DocVarTagTest;
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
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 9],
        ]);
    }

    public function testRuleIgnoresDocBlocks(): void
    {
        $this->ignoreDocBlocks = true;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\DocVarTagTest;
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
            'test',
            Constraint::ShouldNot,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::TARGET, false)]
        );

        return new DocVarTagRule(
            new StatementBuilder($testParser),
            new Configuration($this->ignoreDocBlocks, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
