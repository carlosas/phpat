<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\DocThrowsTagRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DocThrowsTagRule>
 * @internal
 * @coversNothing
 */
class DocThrowsTagTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldNotDepend\DocThrowsTagTest\Subject';
    private const TARGET = 'Fixture\ShouldNotDepend\DocThrowsTagTest\Target';

    private bool $ignoreDocBlocks = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\DocThrowsTagTest;
            class Target extends \Exception {}
            class Subject
            {
                /** @throws Target */
                public function method() {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 7],
        ]);
    }

    public function testRuleIgnoresDocBlocks(): void
    {
        $this->ignoreDocBlocks = true;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\DocThrowsTagTest;
            class Target extends \Exception {}
            class Subject
            {
                /** @throws Target */
                public function method() {}
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

        return new DocThrowsTagRule(
            new StatementBuilder($testParser),
            new Configuration($this->ignoreDocBlocks, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
