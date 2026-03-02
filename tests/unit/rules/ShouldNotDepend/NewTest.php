<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldNotDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Relation\Depend\NewRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<NewRule>
 * @internal
 * @coversNothing
 */
class NewTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldNotDepend\NewTest\Subject';
    private const TARGET = 'Fixture\ShouldNotDepend\NewTest\Target';

    private string $subject = self::SUBJECT;

    private string $target = self::TARGET;

    private bool $ignoreBuiltInClasses = true;

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\NewTest;
            class Target {}
            class Subject
            {
                public function create(): Target
                {
                    return new Target();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', self::SUBJECT, self::TARGET), 8],
        ]);
    }

    public function testRuleDetectsBuiltInClasses(): void
    {
        $this->subject = 'Fixture\ShouldNotDepend\IsStandardClassTest\Subject';
        $this->target = 'Exception';
        $this->ignoreBuiltInClasses = false;

        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldNotDepend\IsStandardClassTest;
            class Subject
            {
                public function method(): \Exception
                {
                    return new \Exception();
                }
            }
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should not depend on %s', $this->subject, $this->target), 7],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::ShouldNot,
            'depend',
            [new Classname($this->subject, false)],
            [new Classname($this->target, false)]
        );

        return new NewRule(
            new StatementBuilder($testParser),
            new Configuration(false, $this->ignoreBuiltInClasses, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
