<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\ShouldBeNamed;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Constraint;
use PHPat\Rule\Assertion\Declaration\Named\ClassnameRule;
use PHPat\Selector\Classname;
use PHPat\Statement\StatementBuilder;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\unit\CreatesPhpFile;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<ClassnameRule>
 * @internal
 * @coversNothing
 */
class ClassnameTest extends RuleTestCase
{
    use CreatesPhpFile;

    private const SUBJECT = 'Fixture\ShouldBeNamed\ClassnameTest\Subject';

    public function testRule(): void
    {
        $file = $this->createPhpFile(<<<'PHP'
            <?php
            namespace Fixture\ShouldBeNamed\ClassnameTest;
            class Subject {}
            PHP);

        $this->analyse([$file], [
            [sprintf('%s should be named SuperCoolClass', self::SUBJECT), 3],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            'test',
            Constraint::Should,
            'beNamed',
            [new Classname(self::SUBJECT, false)],
            [],
            [],
            ['isRegex' => false, 'classname' => 'SuperCoolClass']
        );

        return new ClassnameRule(
            new StatementBuilder($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
