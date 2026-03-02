<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldHaveOnlyOnePublicMethod;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\OnePublicMethod\HasOnlyOnePublicMethodRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<HasOnlyOnePublicMethodRule>
 * @internal
 * @coversNothing
 */
class GoodImplementationClassWithOnlyOnePublicMethodTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldHaveOnlyOnePublicMethod\GoodImplementationClassWithOnlyOnePublicMethodTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldHaveOnlyOnePublicMethod\GoodImplementationClassWithOnlyOnePublicMethodTest;
            class Subject
            {
                public function doSomething(): void {}
                private function helper(): void {}
            }
            PHP);

        $this->analyse([$file], []);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'haveOnlyOnePublicMethod',
            [new Classname(self::SUBJECT, false)],
            []
        );

        return new HasOnlyOnePublicMethodRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
