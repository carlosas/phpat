<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

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

    private const SUBJECT = 'Fixture\CanOnlyDepend\DocThrowsTagTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\DocThrowsTagTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\DocThrowsTagTest\Target';

    private bool $ignoreDocBlocks = false;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\DocThrowsTagTest;
            class Allowed extends \Exception {}
            class Target extends \Exception {}
            class Subject
            {
                /** @throws Target */
                public function method() {}
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 8],
        ]);
    }

    public function testRuleIgnoresDocBlocks(): void
    {
        $this->ignoreDocBlocks = true;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\CanOnlyDepend\DocThrowsTagTest;
            class Allowed extends \Exception {}
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
            Constraint::CanOnly,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::ALLOWED, false)]
        );

        return new DocThrowsTagRule(
            new StatementBuilder($testParser),
            new Configuration($this->ignoreDocBlocks, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
