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

    public const RULE_NAME = 'testCanOnlyDependDocThrowsTag';
    private const SUBJECT = 'Fixture\CanOnlyDepend\DocThrowsTagTest\Subject';
    private const ALLOWED = 'Fixture\CanOnlyDepend\DocThrowsTagTest\Allowed';
    private const TARGET = 'Fixture\CanOnlyDepend\DocThrowsTagTest\Target';

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

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            Constraint::CanOnly,
            'depend',
            [new Classname(self::SUBJECT, false)],
            [new Classname(self::ALLOWED, false)]
        );

        return new DocThrowsTagRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
